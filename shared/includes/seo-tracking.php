<?php

/**
 * Configuration et intégration des outils de tracking et SEO
 * Google Analytics 4, Search Console, et autres outils de mesure
 */

class SEOTrackingConfig
{

  private $config = [
    'google_analytics_id' => 'G-XXXXXXXXXX', // À remplacer par votre ID GA4
    'google_tag_manager_id' => 'GTM-XXXXXXX', // À remplacer par votre ID GTM
    'google_site_verification' => 'xxxxxxxxxxxxxxxxxxxxxxxxx', // À remplacer par votre code de vérification
    'facebook_pixel_id' => 'xxxxxxxxxx', // À remplacer par votre Pixel ID
    'hotjar_id' => 'xxxxxxx', // À remplacer par votre ID Hotjar
    'cookie_consent_enabled' => true,
    'debug_mode' => false
  ];

  /**
   * Génère le code Google Analytics 4
   */
  public function getGoogleAnalyticsCode()
  {
    if (empty($this->config['google_analytics_id'])) {
      return '';
    }

    $gaId = $this->config['google_analytics_id'];
    $debugMode = $this->config['debug_mode'] ? 'true' : 'false';

    return "
        <!-- Google Analytics 4 -->
        <script async src=\"https://www.googletagmanager.com/gtag/js?id={$gaId}\"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{$gaId}', {
                'debug_mode': {$debugMode},
                'anonymize_ip': true,
                'cookie_flags': 'SameSite=Strict;Secure'
            });
            
            // Événements personnalisés pour EJERI Jardins
            function trackServiceInterest(service) {
                gtag('event', 'service_interest', {
                    'service_type': service,
                    'page_location': window.location.href
                });
            }
            
            function trackQuoteRequest(service) {
                gtag('event', 'quote_request', {
                    'service_type': service,
                    'event_category': 'engagement',
                    'event_label': 'devis_demande'
                });
            }
            
            function trackPhoneClick() {
                gtag('event', 'phone_click', {
                    'event_category': 'contact',
                    'event_label': 'telephone'
                });
            }
        </script>
        ";
  }

  /**
   * Génère le code Google Tag Manager
   */
  public function getGoogleTagManagerCode()
  {
    if (empty($this->config['google_tag_manager_id'])) {
      return '';
    }

    $gtmId = $this->config['google_tag_manager_id'];

    return [
      'head' => "
            <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','{$gtmId}');</script>
            <!-- End Google Tag Manager -->
            ",
      'body' => "
            <!-- Google Tag Manager (noscript) -->
            <noscript><iframe src=\"https://www.googletagmanager.com/ns.html?id={$gtmId}\"
            height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->
            "
    ];
  }

  /**
   * Génère les balises de vérification pour Search Console
   */
  public function getSearchConsoleVerification()
  {
    if (empty($this->config['google_site_verification'])) {
      return '';
    }

    return '<meta name="google-site-verification" content="' . $this->config['google_site_verification'] . '">';
  }

  /**
   * Génère le code Facebook Pixel
   */
  public function getFacebookPixelCode()
  {
    if (empty($this->config['facebook_pixel_id'])) {
      return '';
    }

    $pixelId = $this->config['facebook_pixel_id'];

    return "
        <!-- Facebook Pixel -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{$pixelId}');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img height=\"1\" width=\"1\" style=\"display:none\" 
                 src=\"https://www.facebook.com/tr?id={$pixelId}&ev=PageView&noscript=1\"/>
        </noscript>
        ";
  }

  /**
   * Génère le code de consentement aux cookies (RGPD)
   */
  public function getCookieConsentCode()
  {
    if (!$this->config['cookie_consent_enabled']) {
      return '';
    }

    return "
        <!-- Cookie Consent Banner -->
        <div id=\"cookie-consent-banner\" class=\"cookie-consent-banner\" style=\"display:none;\">
            <div class=\"container\">
                <div class=\"row align-items-center\">
                    <div class=\"col-md-8\">
                        <p class=\"mb-0\">
                            <i class=\"bi bi-info-circle me-2\"></i>
                            Nous utilisons des cookies pour améliorer votre expérience et analyser notre trafic. 
                            En continuant à utiliser ce site, vous acceptez notre utilisation des cookies.
                        </p>
                    </div>
                    <div class=\"col-md-4 text-end\">
                        <button id=\"accept-cookies\" class=\"btn btn-primary btn-sm me-2\">Accepter</button>
                        <button id=\"decline-cookies\" class=\"btn btn-outline-secondary btn-sm\">Refuser</button>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            // Gestion du consentement aux cookies
            document.addEventListener('DOMContentLoaded', function() {
                const banner = document.getElementById('cookie-consent-banner');
                const acceptBtn = document.getElementById('accept-cookies');
                const declineBtn = document.getElementById('decline-cookies');
                
                // Vérifier si le consentement a déjà été donné
                if (!localStorage.getItem('cookieConsent')) {
                    banner.style.display = 'block';
                }
                
                acceptBtn.addEventListener('click', function() {
                    localStorage.setItem('cookieConsent', 'accepted');
                    banner.style.display = 'none';
                    // Activer les cookies de tracking
                    enableTracking();
                });
                
                declineBtn.addEventListener('click', function() {
                    localStorage.setItem('cookieConsent', 'declined');
                    banner.style.display = 'none';
                    // Désactiver les cookies de tracking
                    disableTracking();
                });
                
                function enableTracking() {
                    // Activer Google Analytics uniquement si accepté
                    if (typeof gtag !== 'undefined') {
                        gtag('consent', 'update', {
                            'analytics_storage': 'granted'
                        });
                    }
                }
                
                function disableTracking() {
                    // Désactiver le tracking
                    if (typeof gtag !== 'undefined') {
                        gtag('consent', 'update', {
                            'analytics_storage': 'denied'
                        });
                    }
                }
            });
        </script>
        
        <style>
            .cookie-consent-banner {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: #343a40;
                color: white;
                padding: 1rem 0;
                z-index: 9999;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.3);
            }
            
            .cookie-consent-banner p {
                font-size: 0.9rem;
            }
            
            @media (max-width: 768px) {
                .cookie-consent-banner .col-md-4 {
                    text-align: center !important;
                    margin-top: 1rem;
                }
            }
        </style>
        ";
  }

  /**
   * Génère le code complet de tracking pour une page
   */
  public function getCompleteTrackingCode()
  {
    $code = [
      'head' => '',
      'body_start' => '',
      'body_end' => ''
    ];

    // Meta tags
    $code['head'] .= $this->getSearchConsoleVerification();

    // Google Analytics
    $code['head'] .= $this->getGoogleAnalyticsCode();

    // Google Tag Manager
    $gtm = $this->getGoogleTagManagerCode();
    if ($gtm) {
      $code['head'] .= $gtm['head'];
      $code['body_start'] .= $gtm['body'];
    }

    // Facebook Pixel
    $code['head'] .= $this->getFacebookPixelCode();

    // Cookie Consent
    $code['body_end'] .= $this->getCookieConsentCode();

    return $code;
  }

  /**
   * Génère les événements de tracking spécifiques à EJERI Jardins
   */
  public function getCustomTrackingEvents()
  {
    return "
        <script>
            // Événements personnalisés pour EJERI Jardins
            document.addEventListener('DOMContentLoaded', function() {
                // Tracking des clics sur les services
                const serviceLinks = document.querySelectorAll('a[href*=\"service\"]');
                serviceLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        const service = this.getAttribute('href').split('/').pop().replace('.html', '');
                        if (typeof trackServiceInterest === 'function') {
                            trackServiceInterest(service);
                        }
                    });
                });
                
                // Tracking des formulaires de devis
                const quoteForm = document.querySelector('form[action*=\"message\"]');
                if (quoteForm) {
                    quoteForm.addEventListener('submit', function() {
                        if (typeof trackQuoteRequest === 'function') {
                            trackQuoteRequest('contact_form');
                        }
                    });
                }
                
                // Tracking des clics téléphone
                const phoneLinks = document.querySelectorAll('a[href^=\"tel:\"]');
                phoneLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        if (typeof trackPhoneClick === 'function') {
                            trackPhoneClick();
                        }
                    });
                });
                
                // Tracking du scroll (engagement)
                let scrollTracked = false;
                window.addEventListener('scroll', function() {
                    if (!scrollTracked && window.scrollY > window.innerHeight * 0.8) {
                        scrollTracked = true;
                        if (typeof gtag !== 'undefined') {
                            gtag('event', 'scroll_80_percent', {
                                'event_category': 'engagement'
                            });
                        }
                    }
                });
            });
        </script>
        ";
  }
}

// Utilisation
if (php_sapi_name() !== 'cli') {
  // Exemple d'utilisation dans une page
  $tracking = new SEOTrackingConfig();

  // Pour inclure dans le <head>
  // echo $tracking->getCompleteTrackingCode()['head'];

  // Pour inclure après <body>
  // echo $tracking->getCompleteTrackingCode()['body_start'];

  // Pour inclure avant </body>
  // echo $tracking->getCompleteTrackingCode()['body_end'];
  // echo $tracking->getCustomTrackingEvents();
}
