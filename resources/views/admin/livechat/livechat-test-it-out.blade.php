<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ lang('Document') }}</title>
    <style>
        .chat-message-popup .popup-messages-footer > textarea{
            width: 88% !important
        }
    </style>
</head>
<body>
    <script src="{{url('')}}/build/assets/plugins/livechat/liveChat.js" domainName="{{url('')}}"  wsPort='{{setting('liveChatPort')}}' testItOut="{{$flowChatId}}" defer ></script>
</body>
</html>
