<!-- Daily Challenge Modal -->
<style>
/* Basic Reset and Body Styling */
.dc-container * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

.dc-container {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    line-height: 1.6;
    background-color: #e9ecef;
    color: #333;
}

/* Dummy Background Content Styling */
.dc-background-content {
    padding: 20px;
}

/* Modal Styles */
.dc-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    padding: 20px;
}

.dc-modal-content {
    background-color: #fff;
    padding: 25px 30px;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
    max-width: 1143px;
    width: 100%;
    position: relative;
    text-align: center;
    overflow-y: auto;
    max-height: 90vh;
}

.dc-modal-close-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    background: #f1f1f1;
    border: none;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    font-size: 20px;
    font-weight: bold;
    color: #555;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    line-height: 1;
    padding-bottom: 2px;
}

.dc-modal-close-btn:hover {
    background: #e0e0e0;
}

/* Modal Header Title */
.dc-modal-content h2 {
    color: #007bff;
    font-size: 1.6em;
    font-weight: 600;
    margin-bottom: 25px;
    margin-top: 10px;
}

/* Modal Body specific styles */
.dc-modal-body {
    text-align: left;
}

.dc-quest-title {
    font-size: 2.2em;
    font-weight: bold;
    color: #212529;
    text-align: center;
    margin-bottom: 20px;
}

.dc-quest-title span {
    vertical-align: middle;
}

.dc-instructions {
    color: #495057;
    margin-bottom: 25px;
    font-size: 0.95em;
    line-height: 1.7;
}

.dc-highlight {
    color: #007bff;
    font-weight: 500;
}

.dc-separator {
    border: none;
    height: 1px;
    background-color: #dee2e6;
    margin: 30px 0;
}

.dc-modal-body h3 {
    font-size: 1.1em;
    font-weight: 600;
    color: #343a40;
    margin-bottom: 15px;
}

/* Upload Area Styles */
.dc-upload-area {
    border: 2px dashed #adb5bd;
    border-radius: 8px;
    padding: 30px 20px;
    text-align: center;
    background-color: #f8f9fa;
    margin-bottom: 25px;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.dc-upload-area:hover {
    background-color: #e9ecef;
}

.dc-upload-icon {
    width: 40px;
    height: 40px;
    fill: #6c757d;
    margin-bottom: 10px;
}

.dc-upload-text {
    color: #495057;
    font-size: 1em;
    margin-bottom: 8px;
}

.dc-browse-link {
    color: #007bff;
    font-weight: 600;
    text-decoration: none;
}

.dc-browse-link:hover {
    text-decoration: underline;
}

.dc-supported-formats {
    font-size: 0.8em;
    color: #6c757d;
}

/* Upload Button Styles */
.dc-upload-btn {
    display: block;
    width: 100%;
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 1em;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s ease;
    text-transform: uppercase;
}

.dc-upload-btn:hover {
    background-color: #0056b3;
}

/* Navigation Buttons Styles */
.dc-navigation-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #dee2e6;
}

.dc-nav-btn {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 0.9em;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.dc-nav-btn:hover {
    background-color: #0056b3;
}

.dc-nav-btn:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}
</style>

<div class="modal fade dc-modal" id="dailyChallengeModal" tabindex="-1" aria-labelledby="dailyChallengeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content dc-modal-content">
      <button type="button" class="btn-close dc-modal-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
      <h2 class="text-center mt-3">Claim Your Daily Challenge</h2>
      <div class="dc-modal-body">
        @if($course->insightQuests->count())
          @foreach($course->insightQuests as $quest)
            <div class="quest-item" style="display: none;">
              <h1 class="dc-quest-title">"Insight Quest ✨"</h1>
              <div class="challenge-desc mb-4" style="font-size: 1.08rem; color: #444;">
                {{ $quest->description }}
              </div>
              <hr class="dc-separator">
              <div class="submission-status-box" style="display:none"></div>
              <div class="challenge-upload-box mb-3">
                <div class="upload-box-inner text-center">
                  <div class="mb-2" style="font-weight:600; font-size:1.1rem;">Upload</div>
                  <form action="{{ route('insight_quest.upload', $quest->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="dc-upload-area">
                      <svg class="dc-upload-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="48px" height="48px"><path d="M0 0h24v24H0z" fill="none"/><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z"/></svg>
                      <p class="dc-upload-text">Drag & drop files or <a href="#" class="dc-browse-link">Browse</a></p>
                      <p class="dc-supported-formats">Supported formats: JPEG, PNG, GIF, MP4, PDF, PSD, AI, Word, PPT</p>
                      <input type="file" name="files[]" multiple required class="form-control">
                    </div>
                    <button type="submit" class="dc-upload-btn">UPLOAD FILES</button>
                  </form>
                </div>
              </div>
            </div>
          @endforeach
          <div class="dc-navigation-buttons">
            <button class="dc-nav-btn" id="prevBtn" disabled>Previous</button>
            <button class="dc-nav-btn" id="nextBtn">Next</button>
          </div>
        @else
          <div class="text-center py-5">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#6c757d" class="mb-3" viewBox="0 0 16 16">
              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
              <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
            </svg>
            <h3 class="text-muted mb-3">No Challenges Available</h3>
            <p class="text-muted">There are currently no daily challenges available for this course. Please check back later!</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const questItems = document.querySelectorAll('.quest-item');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    let currentIndex = 0;

    // Show first quest initially
    if (questItems.length > 0) {
        questItems[0].style.display = 'block';
        // جلب حالة التسليم لأول مهمة
        const questId = questItems[0].querySelector('form').action.split('/').slice(-2, -1)[0];
        showSubmissionStatus(questItems[0], questId);
    }

    // Update button states
    function updateButtonStates() {
        prevBtn.disabled = currentIndex === 0;
        nextBtn.disabled = currentIndex === questItems.length - 1;
    }

    // Navigation functions
    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            questItems[currentIndex].style.display = 'none';
            currentIndex--;
            questItems[currentIndex].style.display = 'block';
            const questId = questItems[currentIndex].querySelector('form').action.split('/').slice(-2, -1)[0];
            showSubmissionStatus(questItems[currentIndex], questId);
            updateButtonStates();
        }
    });

    nextBtn.addEventListener('click', () => {
        if (currentIndex < questItems.length - 1) {
            questItems[currentIndex].style.display = 'none';
            currentIndex++;
            questItems[currentIndex].style.display = 'block';
            const questId = questItems[currentIndex].querySelector('form').action.split('/').slice(-2, -1)[0];
            showSubmissionStatus(questItems[currentIndex], questId);
            updateButtonStates();
        }
    });

    // Initial button states
    updateButtonStates();

    // Close button functionality
    document.querySelector('.dc-modal-close-btn').onclick = function() {
        document.getElementById('dailyChallengeModal').style.display = 'none';
    };

    // Background click to close
    // (تأكد أن لديك العنصر dc-modal-overlay في الهيكل إذا كنت تستخدمه)
    // document.querySelector('.dc-modal-overlay').onclick = function(e) {
    //     if (e.target === this) {
    //         document.getElementById('dailyChallengeModal').style.display = 'none';
    //     }
    // };
});

/**
 * جلب حالة التسليم وعرضها في العنصر المناسب
 * @param {HTMLElement} questItem العنصر الخاص بالمهمة الحالية
 * @param {string|number} questId رقم المهمة
 */
function showSubmissionStatus(questItem, questId) {
    fetch('/insight-quest/' + questId + '/submission-status')
        .then(res => res.json())
        .then(data => {
            const statusBox = questItem.querySelector('.submission-status-box');
            const uploadBox = questItem.querySelector('.challenge-upload-box');
            if (data.submitted) {
                // أخفي نموذج الرفع
                uploadBox.style.display = 'none';
                // اعرض شاشة التسليم
                let html = '';
                if (data.status === 'pending') {
                    html += `<div class="alert alert-warning" style="margin-bottom:15px;">⏳ Pending Review<br>Your submission is awaiting review by the instructor.</div>`;
                } else if (data.status === 'reviewed') {
                    html += `<div class="alert alert-success" style="margin-bottom:15px;">
                        <b>✅ Task Reviewed</b><br>
                        <b>Grade:</b> <span style="color:#ff9800">${data.grade ?? '-' }%</span><br>
                        <b>Instructor Feedback:</b> <div style="background:#fff;padding:8px 12px;border-radius:6px;margin-top:5px;">${data.feedback ?? '-'}</div>
                    </div>`;
                }
                // عرض الملفات
                if (data.files && data.files.length) {
                    html += `<div style="margin-top:10px;"><b>Submitted Files:</b><ul style="list-style:none;padding-left:0;">`;
                    data.files.forEach(f => {
                        html += `<li style="margin-bottom:7px;">
                            <a href="/storage/${f.file_path}" target="_blank" style="color:#007bff;text-decoration:underline;">${f.file_name}</a>
                            <span style="color:gray;font-size:12px">(${(f.file_size/1024).toFixed(1)} KB)</span>
                        </li>`;
                    });
                    html += `</ul></div>`;
                }
                statusBox.innerHTML = html;
                statusBox.style.display = 'block';
            } else {
                // لم يسلم بعد → أظهر نموذج الرفع
                uploadBox.style.display = 'block';
                statusBox.style.display = 'none';
            }
        });
}
</script> 