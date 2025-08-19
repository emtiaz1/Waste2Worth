<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add EcoCoins - Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #218838;
        }

        .success {
            color: green;
            margin-top: 10px;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Add EcoCoins to User</h2>
        <form id="addCoinsForm">
            @csrf
            <div class="form-group">
                <label for="user_email">User Email:</label>
                <input type="email" id="user_email" name="user_email" required>
            </div>

            <div class="form-group">
                <label for="reason">Reason:</label>
                <input type="text" id="reason" name="reason" placeholder="e.g., Beach Cleanup Event" required>
            </div>

            <div class="form-group">
                <label for="eco_coin_value">EcoCoin Value:</label>
                <input type="number" id="eco_coin_value" name="eco_coin_value" min="1" required>
            </div>

            <button type="submit">Add EcoCoins</button>
        </form>

        <div id="message"></div>
    </div>

    <script>
        document.getElementById('addCoinsForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            try {
                const response = await fetch('/reward/add-coins', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    document.getElementById('message').innerHTML = '<div class="success">' + result.message + '</div>';
                    this.reset();
                } else {
                    document.getElementById('message').innerHTML = '<div class="error">Error adding coins</div>';
                }
            } catch (error) {
                document.getElementById('message').innerHTML = '<div class="error">Error: ' + error.message + '</div>';
            }
        });
    </script>
</body>

</html>