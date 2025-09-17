@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <div class="container py-5">

            <!-- Quiz Header -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold">Practice Quiz: {{ $quiz->title ?? 'Untitled Quiz' }}</h4>
                <div class="d-flex align-items-center">
                    <span id="timer" class="me-2"><i class="fa fa-clock"></i> 10:00</span>
                </div>
            </div>

            <!-- Progress -->
            <div class="mb-4">
                <small id="progress-text">Question 1 of {{ count($quiz->questions) }}</small>
                <div class="progress">
                    <div id="progress-bar"
                         class="progress-bar bg-success"
                         role="progressbar"
                         style="width: 0%">
                    </div>
                </div>
            </div>

            <!-- Quiz Form -->
            <form action="{{ route('quiz.submit', $quiz->id) }}" method="POST">
                @csrf
                @foreach($quiz->questions as $index => $question)
                    <div class="quiz-question card shadow-sm mb-4"
                         data-question="{{ $index }}"
                         style="{{ $index > 0 ? 'display:none;' : '' }}">

                        <div class="card-body">
                            <h5 class="fw-bold mb-3">{{ $question->question_text }}</h5>

                            @foreach($question->options as $option)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio"
                                           name="answers[{{ $question->id }}]"
                                           value="{{ $option->id }}"
                                           id="option-{{ $option->id }}">
                                    <label class="form-check-label" for="option-{{ $option->id }}">
                                        {{ is_array($option->option_text) ? $option->option_text['en'] : $option->option_text }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <!-- Navigation Buttons -->
                <div class="d-flex justify-content-between">
                    <button type="button" id="prev-btn" class="btn btn-secondary" disabled>Previous</button>
                    <button type="button" id="next-btn" class="btn btn-primary">Next</button>
                    <button type="submit" id="submit-btn" class="btn btn-success" style="display:none;">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script for navigation & timer -->
    <script>
        let currentQuestion = 0;
        const totalQuestions = {{ count($quiz->questions) }};
        const questions = document.querySelectorAll('.quiz-question');
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const submitBtn = document.getElementById('submit-btn');

        function updateProgress() {
            let progress = ((currentQuestion + 1) / totalQuestions) * 100;
            progressBar.style.width = progress + "%";
            progressText.innerText = `Question ${currentQuestion + 1} of ${totalQuestions}`;
        }

        function showQuestion(index) {
            questions.forEach((q, i) => {
                q.style.display = (i === index) ? "block" : "none";
            });
            prevBtn.disabled = (index === 0);
            if (index === totalQuestions - 1) {
                nextBtn.style.display = "none";
                submitBtn.style.display = "inline-block";
            } else {
                nextBtn.style.display = "inline-block";
                submitBtn.style.display = "none";
            }
            updateProgress();
        }

        prevBtn.addEventListener('click', () => {
            if (currentQuestion > 0) {
                currentQuestion--;
                showQuestion(currentQuestion);
            }
        });

        nextBtn.addEventListener('click', () => {
            if (currentQuestion < totalQuestions - 1) {
                currentQuestion++;
                showQuestion(currentQuestion);
            }
        });

        // Timer (10 min countdown)
        let timeLeft = 600;
        const timerEl = document.getElementById('timer');
        setInterval(() => {
            if (timeLeft > 0) {
                timeLeft--;
                let minutes = Math.floor(timeLeft / 60);
                let seconds = timeLeft % 60;
                timerEl.innerText = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            }
        }, 1000);

        // Initialize
        showQuestion(currentQuestion);
    </script>
@endsection
