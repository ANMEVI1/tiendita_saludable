document.addEventListener("DOMContentLoaded", () => {
    // Configuración milimétrica del Observer
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        // threshold a 0.10 significa que al 10% de visibilidad se dispara la acción
        threshold: 0.10 
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const element = entry.target;
            const inAnim = element.getAttribute('data-animation') || 'animate__fadeInUp';
            
            // Deducir automáticamente la animación de salida correspondiente
            let outAnimBottom = 'animate__fadeOutDown';
            let outAnimTop = 'animate__fadeOutUp';
            
            if (inAnim.includes('Left')) {
                outAnimBottom = 'animate__fadeOutLeft';
                outAnimTop = 'animate__fadeOutLeft';
            } else if (inAnim.includes('Right')) {
                outAnimBottom = 'animate__fadeOutRight';
                outAnimTop = 'animate__fadeOutRight';
            } else if (inAnim === 'animate__fadeIn' || inAnim === 'animate__fadeInDown') {
                outAnimBottom = 'animate__fadeOut';
                outAnimTop = 'animate__fadeOut';
            }

            if (entry.isIntersecting) {
                // Si el elemento ya tiene la animación de entrada, ignoramos
                // Esto previene que se dispare múltiples veces por re-observación
                if (element.classList.contains(inAnim)) return;

                // Dejamos de observar temporalmente para evitar el "flicker" o parpadeo.
                // ¿Por qué sucede el flicker? Porque las animaciones como fadeInUp mueven el elemento 100% hacia abajo
                // al iniciar, lo cual lo saca del viewport instantáneamente y dispara un evento de salida erróneo.
                observer.unobserve(element);

                element.classList.remove('opacity-0', outAnimBottom, outAnimTop);
                element.classList.add('animate__animated', inAnim);
                
                const handleInEnd = () => {
                    element.removeEventListener('animationend', handleInEnd);
                    // Volvemos a observar una vez que el elemento está en su posición final estática
                    observer.observe(element);
                };
                element.addEventListener('animationend', handleInEnd);

            } else {
                // Si el elemento NO tiene la animación de entrada, significa que ya estaba oculto o es su estado inicial
                if (!element.classList.contains(inAnim)) return;

                // Dejamos de observar temporalmente durante la salida
                observer.unobserve(element);

                // Averiguamos si salió por arriba o por abajo de la pantalla
                const isLeavingTop = entry.boundingClientRect.y < window.innerHeight / 2;
                const outAnim = isLeavingTop ? outAnimTop : outAnimBottom;
                
                element.classList.remove(inAnim);
                element.classList.add('animate__animated', outAnim);
                
                const handleOutEnd = () => {
                    element.removeEventListener('animationend', handleOutEnd);
                    // Reseteamos a invisible
                    element.classList.remove('animate__animated', outAnim);
                    element.classList.add('opacity-0');
                    // Volvemos a observar para futuras interacciones
                    observer.observe(element);
                };
                element.addEventListener('animationend', handleOutEnd);
            }
        });
    }, observerOptions);

    // Seleccionamos y observamos
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    animatedElements.forEach(el => {
        el.classList.add('opacity-0'); 
        // 0.6s hace que se sienta mucho más responsivo y menos "pesado" al hacer scroll rápido
        el.style.animationDuration = '0.6s'; 
        observer.observe(el);
    });
    
    // Suavizado del scroll para anclas (links internos)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if(targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if(targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
