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
</style>
@endsection

@section('main')
    <div class="convo-with">
        Conversation with {{ $user->name }}
    </div>
    <div class="box">
        <div class="conversation">
            @forelse($messages->reverse() as $message)
                <div class="message {{ $message->sender_id == auth()->id() ? 'sent' : 'received' }}">
                    @if($message->type === 'text')
                        {{ $message->content }}
                    @elseif($message->type === 'file')
                        @foreach($message->attachments as $att)
                            <a href="{{ asset('storage/' . $att->url) }}" target="_blank">
                                {{ $att->type }} file
                            </a>
                        @endforeach
                    @endif
                    <small class="timestamp {{ $message->sender_id == auth()->id() ? 'sent' : 'received' }}">{{ $message->created_at->timezone('Asia/Kolkata')->format('h:i A, d M') }}</small>
                </div>
            @empty
                <div>No messages yet.</div>
            @endforelse
        </div>

        <div class="convo-form">
            <form action="{{ route('messages.send', $user->id) }}" method="POST" class="send-message" enctype="multipart/form-data">
                @csrf
                <input class="input" type="text" name="content" placeholder="Type a message">
                {{-- <input type="file" name="attachment"> --}}
                <button class="button" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send-horizontal-icon lucide-send-horizontal"><path d="M3.714 3.048a.498.498 0 0 0-.683.627l2.843 7.627a2 2 0 0 1 0 1.396l-2.842 7.627a.498.498 0 0 0 .682.627l18-8.5a.5.5 0 0 0 0-.904z"/><path d="M6 12h16"/></svg>
                </button>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function formatTimestamp(datetime) {
            const d = new Date(datetime);

            // Hours in 12-hour format
            let hours = d.getHours();
            const minutes = d.getMinutes().toString().padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // 0 => 12

            // Day + Month
            const day = d.getDate();
            const monthNames = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
            const month = monthNames[d.getMonth()];

            return `${hours}:${minutes} ${ampm}, ${day} ${month}`;
        }

        function updateMessages() {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route('messages.fetch', $user->id) }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            })
            .then(res => res.json())
            .then(data => {
                const convo = document.querySelector('.conversation');
                convo.innerHTML = ''; // clear existing messages

                if (data.messages.length === 0) {
                    convo.innerHTML = '<div>No messages yet.</div>';
                    return;
                }

                data.messages.reverse().forEach(message => {
                    const div = document.createElement('div');
                    div.classList.add('message');
                    div.classList.add(message.sender_id == {{ auth()->id() }} ? 'sent' : 'received');

                    if (message.type === 'text') {
                        div.textContent = message.content;
                    } else if (message.type === 'file') {
                        message.attachments.forEach(att => {
                            const a = document.createElement('a');
                            a.href = '/storage/' + att.url;
                            a.target = '_blank';
                            a.textContent = att.type + ' file';
                            div.appendChild(a);
                        });
                    }

                    const small = document.createElement('small');
                    small.classList.add('timestamp', message.sender_id == {{ auth()->id() }} ? 'sent' : 'received');
                    small.textContent = formatTimestamp(message.created_at);
                    div.appendChild(small);

                    convo.appendChild(div);
                });

                convo.scrollTop = convo.scrollHeight; // scroll to bottom
            })
            .catch(err => console.error(err));
        }

        // poll every 1 second
        setInterval(updateMessages, 2000);
    </script>
@endsection
