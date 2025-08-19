
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forum Discussions - Waste2Worth</title>
  <link rel="stylesheet" href="{{ asset('css/forum.css') }}">
  <link rel="stylesheet" href="{{ asset('css/appbar.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
  @include('layouts.appbar')
  <script src="{{ asset('js/appbar.js') }}"></script>
  <div class="layout" id="mainLayout">
    <div class="main-content">
      <div class="forum-bg">
        <div class="forum-card">
      <h2 class="forum-title">Forum Discussions</h2>
  <form action="{{ route('forum.store') }}" method="POST" class="forum-form" enctype="multipart/form-data">
        @csrf
        <div class="forum-input-group">
          <label for="username" class="forum-label">Username</label>
          <input type="text" name="username" id="username" class="forum-input" value="{{ Auth::user()->username ?? Auth::user()->name ?? '' }}" readonly>
        </div>
        <div class="forum-input-group">
          <label for="image" class="forum-label">Add Image <i class="fa fa-image forum-image-icon"></i></label>
          <input type="file" name="image" id="image" class="forum-input" accept="image/*">
        </div>
        <textarea name="message" class="forum-textarea" rows="3" placeholder="Type your discussion here..." required></textarea>
        <button type="submit" class="forum-btn">Post Discussion</button>
      </form>
      @if(session('success'))
        <div class="notification success">{{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="notification error">
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <div class="forum-list">
        <h3 class="forum-list-title">Recent Discussions</h3>
        @forelse($forumDiscussions as $discussion)
          <div class="forum-item">
            <span class="forum-user">{{ $discussion->username }}</span>
            <span class="forum-time">{{ $discussion->created_at->format('M d, Y H:i') }}</span>
            <div class="forum-message">{{ $discussion->message }}</div>
            @if($discussion->image)
              <div class="forum-image">
                <img src="{{ asset('storage/' . $discussion->image) }}" alt="Forum Image" class="forum-image-display">
              </div>
            @endif
          </div>
        @empty
          <div class="forum-empty">No discussions yet. Be the first to post!</div>
        @endforelse
      </div>
      <div class="forum-back">
        <a href="{{ route('community') }}" class="forum-back-link">&larr; Back to Community</a>
      </div>
    </div>
  </div>
</body>
</html>
