<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        #chatbox {
            background: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
        }
        #userInput {
            width: calc(100% - 100px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        #sendBtn {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #sendBtn:hover {
            background-color: #0056b3;
        }
        #faqList {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
        }
        .faqBtn {
            background: #e7e7e7;
            border: none;
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .faqBtn:hover {
            background: #d6d6d6;
        }
        .message {
            margin: 5px 0;
        }
        .user {
            text-align: right;
            color: #007bff;
        }
        .bot {
            text-align: left;
            color: #333;
        }
    </style>
</head>
<body>
<h1>Chatbot</h1>
<div id="chatbox"></div>
<div>
    <input type="text" id="userInput" placeholder="Nhập tin nhắn...">
    <button id="sendBtn">Gửi</button>
</div>

<h2>Câu hỏi thường gặp</h2>
<div id="faqList"></div>

<script>
    $(document).ready(function() {
        // Lấy CSRF token từ meta tag
        let csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Lấy danh sách câu hỏi từ FAQ
        $.ajax({
            url: '/chatbot/faqs', // Đường dẫn API để lấy FAQs
            type: 'GET',
            success: function(data) {
                data.forEach(function(faq) {
                    $('#faqList').append('<button class="faqBtn">' + faq.question + '</button>');
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching FAQs:', xhr.responseText);
            }
        });

        // Xử lý khi người dùng nhấn một câu hỏi
        $(document).on('click', '.faqBtn', function() {
            let message = $(this).text();
            $('#userInput').val(message);
            $('#sendBtn').click(); // Gửi tin nhắn
        });

        $('#sendBtn').click(function() {
            let message = $('#userInput').val();
            $('#chatbox').append('<div class="message user">User: ' + message + '</div>');

            $.ajax({
                url: '/chatbot/chat',
                type: 'POST',
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Thêm CSRF token vào header
                },
                data: JSON.stringify({ message: message }),
                success: function(data) {
                    $('#chatbox').append('<div class="message bot">Bot: ' + data.response + '</div>');
                    $('#chatbox').scrollTop($('#chatbox')[0].scrollHeight); // Cuộn xuống dưới
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr.responseText);
                }
            });

            $('#userInput').val(''); // Xóa ô nhập liệu
            $('#chatbox').scrollTop($('#chatbox')[0].scrollHeight); // Cuộn xuống dưới
        });
    });
</script>
</body>
</html>
