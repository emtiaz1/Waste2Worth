<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Waste2Worth - Report Waste</title>
    <link rel="shortcut icon" href="/frontend/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/appbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reportWaste.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    @include('layouts.appbar')
    <div class="layout" id="mainLayout">
        <div class="main-content">
            <!-- Main Content -->
            <main class="main-content">
                <section class="card">
                    <h2>Report Waste</h2>
                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form id="wasteForm" method="POST" action="{{ route('waste-report.store') }}" enctype="multipart/form-data">
                        @csrf
                        <label>Waste Type
                            <select id="wasteType" name="waste_type" required>
                                <option>Plastic</option>
                                <option>Paper</option>
                                <option>Glass</option>
                                <option>Metal</option>
                                <option>Organic</option>
                                <option>Electronic</option>
                                <option>Other</option>
                            </select>
                        </label>
                        <label>Estimated Amount
                            <div class="input-group">
                                <input type="number" id="wasteAmount" name="amount" placeholder="Amount" required>
                                <select id="wasteUnit" name="unit" required>
                                    <option>kg</option>
                                    <option>lbs</option>
                                </select>
                            </div>
                        </label>
                        <label>Location
                            <input type="text" id="wasteLocation" name="location" placeholder="Location" required>
                        </label>
                        <label>Description
                            <textarea id="wasteDescription" name="description" rows="3" placeholder="Details..."></textarea>
                        </label>
                        <label>Upload Photo
                            <input type="file" id="wasteImage" name="image" accept="image/*">
                        </label>
                        <button type="submit">Submit Report</button>
                        <button type="button" id="fallbackSubmit" style="display:none; margin-left: 10px; background: #666;">Try Alternative Submit</button>
                    </form>
                </section>
                <section class="card">
                    <h3>Recent Waste Reports</h3>
                    <div id="recentReports"></div>
                </section>
            </main>
        </div>
        <script src="{{ asset('js/reportWaste.js') }}"></script>
        <script src="{{ asset('js/appbar.js') }}"></script>
</body>

</html>