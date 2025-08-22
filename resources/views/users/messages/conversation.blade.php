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
                <button class="button" type="submit">Send</button>
            </form>
        </div>
    </div>
@endsection

@section('js')
@endsection
