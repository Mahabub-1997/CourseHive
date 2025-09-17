@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">

        {{-- ===============================
             Dashboard Info Box
        ================================ --}}
        <div class="container-fluid mb-4">
            <div class="row" style="margin-top: 80px;">
                <div class="col-12">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center bg-primary text-white">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 2rem;">My Course</span>
                            <span class="info-box-number" style="font-size: .7rem;">
                            You're making excellent progress in your healthcare training
                        </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===============================
                 Course Header Section
            ================================ --}}
            <div class="content-header">
                <div class="container-fluid">

                    {{-- Course Level --}}
                    <div class="row align-items-center">
                        <div class="col-sm-12 mb-2">
                            @php
                                $levelClass = match($course->level) {
                                    'Beginner' => 'badge bg-primary',
                                    'Intermediate' => 'badge bg-warning text-dark',
                                    'Advanced' => 'badge bg-danger',
                                    default => 'badge bg-secondary',
                                };
                                $levelText = $course->level ?? 'N/A';
                            @endphp
                            <span class="{{ $levelClass }}" style="border-radius: 40px; padding: .25rem .75rem;">
                            {{ $levelText }}
                        </span>
                        </div>
                    </div>

                    {{-- Course Title --}}
                    <div class="row align-items-center">
                        <div class="col-sm-12 mb-2">
                            <h1 class="m-0">{{ $course->title }}</h1>
                        </div>
                    </div>

                    {{-- Course Description --}}
                    <div class="row">
                        <div class="col-sm-12">
                            <p>{{ $course->description }}</p>
                        </div>
                    </div>

                    {{-- Course Info (Rating, Duration, Lessons, Language) --}}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <strong><i class="fas fa-star"></i> :</strong> {{ $ratingPoint ?? 'No rating yet' }}
                                </div>
                                <div class="col-md-3">
                                    <strong><i class="fas fa-clock"></i> :</strong> {{ $course->duration ?? '-' }}
                                </div>
                                <div class="col-md-3">
                                    <strong><i class="fas fa-book"></i> :</strong> {{ $lessonsCount }}
                                </div>
                                <div class="col-md-3">
                                    <strong><i class="fas fa-globe"></i> :</strong> {{ $course->language ?? '-' }}
                                </div>
                            </div>

                            {{-- Creator & Last Updated --}}
                            <div class="row">
                                <strong style="margin-right: 10px;">Created By :</strong>
                                <span class="text-primary me-2"  style="margin-right: 3px;">{{ $course->creator ? $course->creator->name : 'N/A' }}</span>
                                • <span class="fw-light">Last Updated: {{ $course->updated_at ? $course->updated_at->format('F Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ===============================
                 Course Image
            ================================ --}}
            <div class="container-fluid mb-4">
                <div class="card shadow-sm" style="border-radius: 15px;">
                    @if($course->image)
                        <img src="{{ asset('uploads/courses/' . $course->image) }}"
                             class="card-img-top"
                             alt="Course Image"
                             style="height:250px; object-fit:cover; border-top-left-radius:15px; border-top-right-radius:15px;">
                    @endif
                </div>
            </div>

            {{-- ===============================
                 Course Content (Lessons + Parts + Quiz)
            ================================ --}}
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-3">Course Content</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach($lessons as $lesson)
                            <li class="mb-2">
                                {{-- Lesson Title with Arrow --}}
                                <div class="d-flex justify-content-between align-items-center lesson-toggle"
                                     data-bs-toggle="collapse"
                                     data-bs-target="#lesson{{ $lesson->id }}"
                                     aria-expanded="false"
                                     style="cursor: pointer; padding:8px 12px; background:#f8f9fa; border-radius:6px;">
                                    <span class="fw-semibold" style="font-weight: bold;">{{ $lesson->title }}</span>
                                    <i class="bi bi-chevron-down"></i>
                                </div>

                                {{-- Parts of Lesson --}}
                                <div id="lesson{{ $lesson->id }}" class="collapse ps-3 mt-2 text-black">
                                    @if($lesson->parts->count())
                                        <ul class="list-unstyled">
                                            @foreach($lesson->parts as $part)
                                                <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                    {{-- Left: Part Title --}}
                                                    <div>
                                                        <i class="bi bi-play-circle me-2 text-primary"></i>
                                                        {{ $part->title }}
                                                    </div>

                                                    {{-- Right: Video Link --}}
                                                    <div>
                                                        @if($part->video)
                                                            <a href="{{ $part->video }}" target="_blank"
                                                               class="text-decoration-none text-primary">
                                                                <i class="bi bi-camera-video me-1"></i> Video
                                                            </a>
                                                        @endif
                                                    </div>
                                                </li>
                                            @endforeach
                                                {{-- Quiz + Continue Button on same line --}}
                                                <div class="mt-2 d-flex justify-content-between align-items-center">
                                                    <div
                                                       class="badge bg-success text-decoration-none">
                                                        <i class="bi bi-question-circle me-1"></i> Practice Quiz
                                                    </div>

                                                    <a href="{{ route('course.content', $course->id) }}"
                                                       class="btn btn-primary btn-sm px-3">
                                                        Continue <i class="bi bi-arrow-right ms-1"></i>
                                                    </a>
                                                </div>
                                        </ul>
                                    @else
                                        <p class="text-muted">No content available for this lesson.</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Arrow Rotation Style --}}
            <style>
                .lesson-toggle[aria-expanded="true"] i {
                    transform: rotate(180deg);
                    transition: transform 0.3s ease;
                }
                .lesson-toggle[aria-expanded="false"] i {
                    transform: rotate(0deg);
                    transition: transform 0.3s ease;
                }
            </style>

            {{-- ===============================
                 Creator Info Card
            ================================ --}}
            <div class="card shadow-sm p-3 mb-4" style="border-radius: 10px;">
                <div class="d-flex align-items-center">
                    {{-- Creator Profile Image Placeholder --}}
                    <div class="me-3">
                        <div style="width:60px; height:60px; border-radius:50%; background:#ddd;"></div>
                    </div>

                    <div class="flex-grow-1">
                        {{-- Creator Name --}}
                        <h5 class="mb-1"><strong>{{ $course->creator ? $course->creator->name : 'N/A' }}</strong></h5>
                        {{-- Course Title --}}
                        <p class="text-muted mb-2">{{ $course->title }}</p>

                        {{-- Rating & Lessons --}}
                        <div class="d-flex align-items-center text-muted" style="font-size: 0.9rem;">
                        <span class="me-3">
                            <i class="fas fa-star text-warning"></i>
                            {{ $ratingPoint ?? 'No rating' }}
                        </span>
                            <span>
                            <i class="fas fa-book-open"></i> {{ $lessons->count() }} Lessons
                        </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===============================
                 Student Reviews
            ================================ --}}
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-3">Student Reviews</h5>
                </div>
                <div class="card-body">

                    {{-- Review List --}}
                    @foreach($shareExperiances as $review)
                        <div class="d-flex mb-4 pb-3 border-bottom">
                            {{-- Reviewer Avatar Placeholder --}}
                            <div class="rounded-circle bg-secondary me-3" style="width:40px; height:40px;"></div>

                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-0 fw-semibold">{{ $review->user->name ?? $review->name }}</h6>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>

                                {{-- Rating Stars --}}
                                <div class="text-warning mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span>{{ $i <= ($review->rating->rating_point ?? 0) ? '★' : '☆' }}</span>
                                    @endfor
                                </div>

                                {{-- Review Text --}}
                                <p class="mb-0 text-muted">{{ $review->description }}</p>
                            </div>
                        </div>
                    @endforeach

                    {{-- Review Form (Only Authenticated Users) --}}
                    @auth
                        <form action="{{ route('courses.reviews.store', $course->id) }}" method="POST" class="mt-4">
                            @csrf

                            {{-- Star Rating --}}
                            <div class="mb-3">
                                <label class="form-label">Your Rating</label>
                                <div id="star-container" class="mb-2">
                                    <span class="star fs-4" data-value="1" role="button">&#9734;</span>
                                    <span class="star fs-4" data-value="2" role="button">&#9734;</span>
                                    <span class="star fs-4" data-value="3" role="button">&#9734;</span>
                                    <span class="star fs-4" data-value="4" role="button">&#9734;</span>
                                    <span class="star fs-4" data-value="5" role="button">&#9734;</span>
                                </div>
                                <input type="hidden" name="rating" id="rating-input" value="{{ old('rating', 0) }}">
                                @error('rating') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            {{-- Review Description --}}
                            <div class="mb-3">
                                <label for="description" class="form-label">Write Here</label>
                                <textarea name="description" id="description" rows="4" class="form-control" placeholder="Write your review...">{{ old('description') }}</textarea>
                                @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            {{-- Submit Button --}}
                            <button type="submit" class="btn btn-primary rounded-5 px-4">Submit</button>
                        </form>

                        {{-- Star Rating Style & Script --}}
                        <style>
                            .star { cursor: pointer; color: #cfcfcf; margin-right: 6px; user-select: none; }
                            .star.selected { color: #ffb400; }
                        </style>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const stars = document.querySelectorAll('#star-container .star');
                                const input = document.getElementById('rating-input');

                                function setStars(value) {
                                    stars.forEach(s => {
                                        s.classList.toggle('selected', Number(s.dataset.value) <= Number(value));
                                        s.innerHTML = Number(s.dataset.value) <= Number(value) ? '★' : '☆';
                                    });
                                }

                                setStars(input.value || 0);

                                stars.forEach(star => {
                                    star.addEventListener('click', function () {
                                        const v = this.dataset.value;
                                        input.value = v;
                                        setStars(v);
                                    });
                                    star.addEventListener('mouseover', function () {
                                        setStars(this.dataset.value);
                                    });
                                    star.addEventListener('mouseleave', function () {
                                        setStars(input.value || 0);
                                    });
                                });
                            });
                        </script>
                    @else
                        <p class="text-muted mt-4">Please <a href="{{ route('login') }}">log in</a> to leave a review.</p>
                    @endauth
                </div>
            </div>
        </div>
    </div>
@endsection
