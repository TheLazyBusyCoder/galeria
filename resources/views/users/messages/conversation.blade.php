@extends('layouts.user-layout')

@section('css')
<style>
    .conversation {
        border: 1px solid var(--color-border);
        padding: 10px;
        display: flex;
        flex-direction: column;
        background: var(--color-surface-alt);
        width: 100%;
        height: 500px;
        overflow-x: auto;
    }

    .message {
        padding: 8px 12px;
        max-width: 70%;
        color: var(--color-text);
        word-break: break-word;
        display: flex;
        flex-direction: column;
        margin-bottom: 0.5rem;
    }

    .message.sent {
        background: var(--color-primary); /* Your primary brand color */
        color: var(--color-text-inverse);
        align-self: flex-end;
    }

    .message.received {
        background: var(--color-surface); /* Card background */
        color: var(--color-text);
        align-self: flex-start;
        border: 1px solid var(--color-divider);
    }

    form.send-message {
        display: flex;
        gap: 5px;
        margin-top: 10px;
    }

    .box {
        display: flex;
        justify-content: space-between;
        align-items: stretch;
        flex-direction: column;
        height: 100%;
        background-color: var(--color-bg);
    }

    .convo-form {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .input {
        min-width: 250px;
    }

    .timestamp {
        text-align: end;
        font-size: 0.7rem;
    }

    .timestamp.sent {
        text-align: end;
    }
    .timestamp.received {
        text-align: start;
    }

    hr {
        border: 1px solid var(--color-border);
    }

    .convo-with {
        text-align: center;
    }

    .input {
        border-radius: 0;
    }

    .button {
        border-radius: 0;
        padding: 5px;
    }

    #mic-btn {
        width: 50px;
    }
</style>
<style>
    /* Play button + audio */
    .audio-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .play-btn {
        height: 36px;
        width: 100%;
        border-radius: 0;
        border: none;
        background: var(--color-primary);
        color: var(--color-text-inverse);
        font-size: 16px;
        cursor: pointer;
        box-shadow: 0 2px 4px var(--color-shadow);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .play-btn:hover {
        background: var(--color-secondary);
    }

    /* Hide native audio controls */
    .audio-wrapper audio {
        display: none;
    }

</style>
@endsection
@section('main')
<div class="convo-with">
    Conversation with {{ $user->name }}
</div>
<div class="box">
    <div class="conversation">
        {{-- messages will be filled dynamically by JS (updateMessages) --}}
    </div>

    <div class="convo-form">
        {{-- text form --}}
        <form action="{{ route('messages.send', $user->id) }}" method="POST" class="send-message" enctype="multipart/form-data">
            @csrf
            <input class="input" type="text" name="content" placeholder="Type a message">
            <button class="button" type="submit">Send</button>
            {{-- voice form (handled by JS) --}}
            <button type="button" id="mic-btn" class="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mic-icon lucide-mic"><path d="M12 19v3"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><rect x="9" y="2" width="6" height="13" rx="3"/></svg>
            </button>
        </form>
    </div>
</div>
@endsection
@section('js')
<script>
    let mediaRecorder;
    let audioChunks = [];
    const micBtn = document.getElementById('mic-btn');
    let isRecording = false;

    micBtn.addEventListener('click', async () => {
        if (!isRecording) {
            // Start recording
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];

                mediaRecorder.ondataavailable = e => {
                    if (e.data.size > 0) {
                        audioChunks.push(e.data);
                    }
                };

                mediaRecorder.onstop = () => {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                    const formData = new FormData();
                    formData.append('voice', audioBlob, 'voice-message.webm');

                    // send to backend
                    fetch("{{ route('messages.sendVoice', $user->id) }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        console.log("Voice message sent", data);
                        updateMessages();
                    })
                    .catch(err => console.error(err));
                };

                mediaRecorder.start();
                isRecording = true;
                micBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-icon lucide-square"><rect width="18" height="18" x="3" y="3" rx="2"/></svg>'; // stop icon
            } catch (err) {
                console.error("Microphone access denied:", err);
            }
        } else {
            // Stop recording
            mediaRecorder.stop();
            isRecording = false;
            micBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mic-icon lucide-mic"><path d="M12 19v3"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><rect x="9" y="2" width="6" height="13" rx="3"/></svg>';
        }
    });

    // --- existing message polling ---
    function formatTimestamp(datetime) {
        const d = new Date(datetime);
        let hours = d.getHours();
        const minutes = d.getMinutes().toString().padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12 || 12;
        const day = d.getDate();
        const monthNames = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
        const month = monthNames[d.getMonth()];
        return `${hours}:${minutes} ${ampm}, ${day} ${month}`;
    }

    let lastMessageId = 0; // keep track of last rendered message

    let firstLoad = true; // flag to ensure scroll only runs once

    function updateMessages() {
        fetch('{{ route('messages.fetch', $user->id) }}')
            .then(res => res.json())
            .then(data => {
                const convo = document.querySelector('.conversation');

                if (!data.messages || data.messages.length === 0) {
                    if (convo.children.length === 0) {
                        convo.innerHTML = '<div>No messages yet.</div>';
                    }
                    return;
                }

                const messages = data.messages;

                messages.forEach(message => {
                    if (message.id <= lastMessageId) return;

                    const div = document.createElement('div');
                    div.classList.add('message');
                    div.classList.add(message.sender_id == {{ auth()->id() }} ? 'sent' : 'received');

                    if (message.type === 'text') {
                        div.textContent = message.content;
                    } 
                    else if (message.type === 'file') {
                        message.attachments.forEach(att => {
                            const a = document.createElement('a');
                            a.href = '/storage/' + att.url;
                            a.target = '_blank';
                            a.textContent = att.type + ' file';
                            div.appendChild(a);
                        });
                    } 
                    else if (message.type === 'voice') {
                        const bubble = document.createElement('div');
                        bubble.classList.add('voice-msg', message.sender_id == {{ auth()->id() }} ? 'sent' : 'received');

                        message.attachments.forEach(att => {
                            const audioWrapper = document.createElement('div');
                            audioWrapper.classList.add('audio-wrapper');

                            const playBtn = document.createElement('button');
                            playBtn.classList.add('play-btn');
                            playBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mic-icon lucide-mic"><path d="M12 19v3"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><rect x="9" y="2" width="6" height="13" rx="3"/></svg>';

                            const audio = document.createElement('audio');
                            audio.src = '/storage/' + att.url;
                            audio.dataset.messageId = message.id;

                            playBtn.addEventListener('click', () => {
                                if (audio.paused) {
                                    audio.play();
                                } else {
                                    audio.pause();
                                }
                            });

                            audioWrapper.appendChild(playBtn);
                            audioWrapper.appendChild(audio);
                            bubble.appendChild(audioWrapper);
                        });

                        div.appendChild(bubble);
                    }

                    const small = document.createElement('small');
                    small.classList.add('timestamp', message.sender_id == {{ auth()->id() }} ? 'sent' : 'received');
                    small.textContent = formatTimestamp(message.created_at);
                    div.appendChild(small);

                    convo.appendChild(div);

                    if (message.id > lastMessageId) {
                        lastMessageId = message.id;
                    }
                });

                // 👇 scroll to bottom only once on first load
                if (firstLoad) {
                    convo.scrollTop = convo.scrollHeight;
                    firstLoad = false;
                }
            })
            .catch(err => console.error(err));
    }

    window.addEventListener("load", function() {
        const convo = document.querySelector('.conversation');
        if (convo) {
            console.log('fuck');
            convo.scrollTop = convo.scrollHeight;
        }
    });

    updateMessages();

    setInterval(updateMessages, 2000);
</script>
@endsection

