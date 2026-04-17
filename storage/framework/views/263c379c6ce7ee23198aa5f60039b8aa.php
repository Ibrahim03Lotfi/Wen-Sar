<!-- Splash Screen -->
<div id="splash-screen" class="fixed inset-0 z-[9999] flex items-center justify-center overflow-hidden" style="background-color: #06402b; width: 100vw; height: 100vh; min-height: 100vh; min-width: 100vw; max-height: 100vh; max-width: 100vw;">
    <!-- Invisible floor line (middle of page) -->
    <div class="absolute w-full h-1" style="top: 50%; background: transparent;"></div>
    
    <!-- Logo png aa - Falls from top -->
    <div id="splash-logo" class="absolute" style="width: 280px; height: 280px;">
        <img src="<?php echo e(asset('images/logo png aa.png')); ?>" alt="Logo" class="w-full h-full object-contain drop-shadow-2xl">
    </div>
    
    <!-- Wensar white - Comes from bottom -->
    <div id="splash-text" class="absolute" style="width: 180px; height: 180px; opacity: 0;">
        <img src="<?php echo e(asset('images/wensar white png.png')); ?>" alt="وين صار" class="w-full h-full object-contain drop-shadow-2xl">
    </div>
    
    <!-- Tagline - Typing animation -->
    <div id="splash-tagline" class="absolute text-white text-2xl md:text-3xl font-bold tracking-wide opacity-0" style="top: calc(50% + 170px);">
        <span id="typing-text"></span><span class="animate-pulse">|</span>
    </div>
</div>

<style>
    /* Hide body scroll and content behind splash */
    body.splash-active {
        overflow: hidden !important;
        background-color: #06402b !important;
    }

    body.splash-active #app,
    body.splash-active main,
    body.splash-active header {
        visibility: hidden;
    }
    
    @keyframes fallFromTop {
        0% {
            top: -400px;
            transform: translateX(-50%) scale(0.7) rotate(-10deg);
            opacity: 0;
        }
        30% {
            opacity: 1;
        }
        60% {
            top: calc(50% - 140px);
            transform: translateX(-50%) scale(1.05) rotate(3deg);
        }
        75% {
            top: calc(50% - 160px);
            transform: translateX(-50%) scale(0.98) rotate(-2deg);
        }
        85% {
            top: calc(50% - 140px);
            transform: translateX(-50%) scale(1.02) rotate(1deg);
        }
        100% {
            top: calc(50% - 140px);
            transform: translateX(-50%) scale(1) rotate(0deg);
            opacity: 1;
        }
    }
    
    @keyframes riseFromBottom {
        0% {
            top: calc(50% + 400px);
            transform: translateX(-50%) scale(0.7);
            opacity: 0;
        }
        30% {
            opacity: 1;
        }
        60% {
            top: calc(50% - 60px);
            transform: translateX(-50%) scale(1.05);
        }
        75% {
            top: calc(50% - 80px);
            transform: translateX(-50%) scale(0.98);
        }
        85% {
            top: calc(50% - 60px);
            transform: translateX(-50%) scale(1.02);
        }
        100% {
            top: calc(50% - 60px);
            transform: translateX(-50%) scale(1);
            opacity: 1;
        }
    }
    
    @keyframes fadeOut {
        0% {
            opacity: 1;
        }
        100% {
            opacity: 0;
            visibility: hidden;
        }
    }
    
    @keyframes glowPulse {
        0%, 100% {
            filter: drop-shadow(0 0 25px rgba(255,255,255,0.3));
        }
        50% {
            filter: drop-shadow(0 0 50px rgba(255,255,255,0.5));
        }
    }
    
    #splash-logo {
        left: 50%;
        animation: 
            fallFromTop 1.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards,
            glowPulse 2s ease-in-out 1.4s infinite;
    }
    
    #splash-text {
        left: 50%;
        animation: riseFromBottom 1.3s cubic-bezier(0.25, 0.46, 0.45, 0.94) 0.8s forwards;
    }
    
    #splash-screen.fade-out {
        animation: fadeOut 0.6s ease-out forwards;
    }
    
    #splash-tagline {
        left: 50%;
        transform: translateX(-50%);
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        white-space: nowrap !important;
        text-align: center;
        padding: 0 10px;
        max-width: 95vw;
    }
    
    @media (max-width: 640px) {
        #splash-tagline {
            font-size: 1.1rem;
            top: calc(50% + 40px) !important;
            padding: 0 5px;
        }
        
        #splash-logo {
            width: 180px !important;
            height: 180px !important;
        }
        
        #splash-text {
            width: 140px !important;
            height: 140px !important;
        }
    }
    
    @media (min-width: 641px) {
        #splash-logo {
            width: 200px !important;
            height: 200px !important;
        }
        
        #splash-text {
            width: 180px !important;
            height: 180px !important;
        }
    }
    
    #splash-tagline.show {
        opacity: 1;
        transition: opacity 0.3s ease;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const splash = document.getElementById('splash-screen');

        // Check if splash was already shown in this session
        if (sessionStorage.getItem('splashShown')) {
            splash.style.display = 'none';
            return;
        }

        // Mark splash as shown for this session
        sessionStorage.setItem('splashShown', 'true');
        
        const tagline = document.getElementById('splash-tagline');
        const typingText = document.getElementById('typing-text');
        const text = 'سوريا كاملة بين أيديك';
        
        // Add splash-active class to body
        document.body.classList.add('splash-active');
        
        // Start typing animation after logos land (2.1s)
        setTimeout(() => {
            tagline.classList.add('show');
            let index = 0;
            const totalDuration = 2000; // 2 seconds
            const charDuration = totalDuration / text.length;
            
            const typeChar = () => {
                if (index < text.length) {
                    typingText.textContent += text.charAt(index);
                    index++;
                    setTimeout(typeChar, charDuration);
                }
            };
            
            typeChar();
        }, 2100);
        
        // Hide splash screen after all animations complete
        setTimeout(() => {
            splash.classList.add('fade-out');
            document.body.classList.remove('splash-active');
            
            // Remove from DOM after fade out
            setTimeout(() => {
                splash.style.display = 'none';
            }, 600);
        }, 5000);
    });
</script>
<?php /**PATH C:\Users\LENOVO\Desktop\Wen-Sar\resources\views/components/splash-screen.blade.php ENDPATH**/ ?>