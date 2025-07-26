<!-- Splash Screen Component -->
<div id="splashScreen" class="splash-screen" style="display: none;">
    <div class="splash-content">
        <video autoplay muted playsinline class="splash-video">
            <source src="{{ asset('Photos/delay screen.mp4') }}" type="video/mp4">
        </video>
    </div>
</div>

<style>
.splash-screen {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #ffffff;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.splash-content {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.splash-video {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
</style>

<script>
// Function to show splash screen during page transition
function showSplashScreen() {
    const splashScreen = document.getElementById('splashScreen');
    splashScreen.style.display = 'flex';
    
    // Hide splash screen after 4 seconds
    setTimeout(() => {
        splashScreen.style.display = 'none';
    }, 4000);
}

// Add event listener to all forms that should show the splash screen
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            showSplashScreen();
        });
    });

    // Add event listener to Google sign-in button
    const googleButtons = document.querySelectorAll('.btn-google');
    googleButtons.forEach(button => {
        button.addEventListener('click', function() {
            showSplashScreen();
        });
    });
});
</script> 