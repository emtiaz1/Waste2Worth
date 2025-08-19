<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Waste2Worth - Community</title>

  <link rel="stylesheet" href="{{ asset('css/community.css') }}" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/appbar.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
  @include('layouts.appbar')
  <div class="layout" id="mainLayout"><div class="main-content">
    <section class="intro">
      <h2>Welcome to Our Community</h2>
      <p>Connect, share, and collaborate with eco-warriors working together to transform waste into worth. Join groups, discuss ideas, and take part in sustainability campaigns.</p>
    </section>

    <section class="community-actions">
  <div class="full-card">
        <h3>️ Forum Discussions</h3>
        <p>Share ideas, ask questions, and discuss solutions with the Waste2Worth community.</p>
        <a href="{{ route('forum.index') }}"><button>Go to Forum</button></a>
      </div>
    </section>

    <section class="testimonials">
  <div class="full-card">
        <h2>Community Comments</h2>
        @foreach($forumDiscussions as $discussion)
          <div class="testimonial">
            <span class="user">{{ $discussion->username }}</span>
            <div class="comment">{{ $discussion->message }}</div>
            @if($discussion->image)
              <div class="comment-image">
                <img src="{{ asset('storage/' . $discussion->image) }}" alt="Forum Image" class="community-image">
              </div>
            @endif
            <div class="actions">
              <span>{{ $discussion->created_at->format('M d, Y H:i') }}</span>
            </div>
          </div>
        @endforeach
      </div>
    </section>
  <script src="{{ asset('js/appbar.js') }}"></script>
</body>
</html>
