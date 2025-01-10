    <!DOCTYPE html>
    <html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <title>كوبونكو</title>
        <style>
    @font-face {
        font-family: 'Amazon';
        src: url('../fonts/AmazonEmberV2-Bold.ttf') format('truetype');
        font-weight: 700;
        font-style: 700;
    }        body {
                font-family: 'Amazon', sans-serif;
            }
            .coupon-card {
                transition: all 0.3s ease;
            }
            .coupon-card:hover {
                transform: translateY(-2px);
            }
            .hover-scale {
                transition: transform 0.2s ease;
            }
            .hover-scale:hover {
                transform: scale(1.05);
            }
            .stats-item {
                transition: all 0.2s ease;
            }
            .stats-item:hover {
                background-color: #f8fafc;
            }
            .coupon-button {
                position: relative;
                padding: 12px 32px;
                background: white;
                border: 2px solid #2563eb;
                border-radius: 10px;
                color: #2563eb;
                font-weight: 500;
                font-size: 14px;
                transition: all 0.3s ease;
                transform-style: preserve-3d;
                box-shadow: 6px 6px 0 #2563eb;
            }

            .coupon-button:hover {
                transform: translate(-2px, -2px);
                box-shadow: 8px 8px 0 #2563eb;
            }

            .coupon-button:active {
                transform: translate(2px, 2px);
                box-shadow: 4px 4px 0 #2563eb;
            }

            .coupon-card {
                border: 1px solid #e5e7eb;
                padding: 24px;
                border-radius: 16px;
            }

            .discount-badge {
                background: #eff6ff;
                padding: 12px 24px;
                border-radius: 12px;
                color: #2563eb;
                font-size: 24px;
                font-weight: bold;
            }

            @media (max-width: 768px) {
                .stats-mobile-hidden {
                    display: none;
                }
            }

            .blog-card {
                transition: all 0.3s ease;
                border: 1px solid #e5e7eb;
                border-radius: 16px;
            }
            
            .blog-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }



            .button {
    text-align: left;
    font-size: 14px;
    line-height: 22px;
    font-weight: 400;
    border: 0;
    position: relative;
    border-radius: 6px;
    min-width: 222px;
    padding: 8px;
    text-transform: uppercase;
    color: #444444;
    background: #EAEAEA;
    cursor: pointer;
    margin-top: 0;
}

.code-text {
    text-align: right;
    display: block;
    padding-right: 8px;
}

.button .layer {
    position: absolute;
    left: 0;
    top: 0;
    color: #ffffff;
    background: linear-gradient(90deg, #2563eb, #1d4ed8);
    font-size: 14px;
    border-radius: 4px;
    transition: all 0.5s ease;
    min-width: 85%;
    text-align: left;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.button:hover .layer {
    min-width: 75%;
}
.button {
    min-width: 180px; /* Reduced from 222px */
    font-size: 12px; /* Reduced from 14px */
}

        </style>
    </head>
    <body class="bg-gray-50">
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-6xl mx-auto px-10">
                <div class="flex justify-between items-center h-16">
                    <a href="#" class="text-2xl font-bold text-blue-600 hover:text-blue-700">Demo Coupoun</a>
                    <div class="hidden md:flex items-center gap-6">
                        <div class="text-sm text-gray-600">
                            افضـل الكوبونات في الوطن العربي
                    </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-6xl mx-auto px-10 py-6 ">
        <div class="w-full bg-white rounded-xl shadow-sm border mb-2">
        <div class="px-6 pt-6 pb-2"> 
        <div class="flex flex-col sm:flex-row gap-8 items-start">

        <div class="w-full sm:w-[210px] flex-shrink-0">
            <img src="https://couponku.com/wp-content/uploads/2024/10/%D9%83%D9%88%D8%AF-%D8%AE%D8%B5%D9%85-%D9%81%D8%B3%D8%A7%D8%AA%D9%8A%D9%86-%D8%A8%D8%A7%D8%B1%D9%84%D9%8A%D9%86%D8%A7.png" 
                alt="Barlina" 
                class="w-full object-contain hover-scale"/>
        </div>

<div class="flex-1 text-right">
    <h1 class="text-base md:text-2xl font-bold mb-3">كود خصم بارلينا 2024: أفضل أكواد خصم barllina لأفضل فساتين</h1>
    <p class="text-xs md:text-sm mb-4">أفضل أكواد خصم barilina لأفضل فساتين</p>
    <div class="flex gap-5 max-w-md">
        <button class="flex-1 py-3 bg-blue-600 text-white rounded-lg text-xs md:text-sm font-medium hover:bg-blue-700 transition-colors">
            الكل (3)
        </button>
        <button class="flex-1 py-3 bg-blue-50 text-blue-600 rounded-lg text-xs md:text-sm font-medium hover:bg-blue-100 transition-colors">
            الكوبونات (3)
        </button>
        <button class="flex-1 py-3 bg-blue-50 text-blue-600 rounded-lg text-xs md:text-sm font-medium hover:bg-blue-100 transition-colors">
            العروض (0)
        </button>
    </div>
</div>
    </div>
        </div>

    </div>
            <div class=" flex flex-col lg:flex-row-reverse gap-6 ">
                <div class="flex-1">



                <div class="space-y-6 bg-white mt-4">
                <div class="coupon-card border border-gray-200 hover:border-blue-200 rounded-xl p-2 md:p-4">
                <!-- Main content container -->
        <div class="flex flex-row items-center gap-2 md:gap-4 mb-0 md:mb-4">
        <!-- Discount box -->
            <div class="text-blue-600 text-xl md:text-2xl font-bold bg-blue-50 px-2 py-4 md:py-6 rounded-xl w-[90px] md:w-[110px] shrink-0 flex items-center justify-center">
                <div class="text-center leading-[1.2] tracking-tight">خصم 30%</div>
            </div>
            
            <!-- Title -->
            <div class="flex-1 flex flex-col md:flex-row md:items-center md:justify-between">
                <h3 class="font-bold text-sm md:text-xl mb-0">كوبون خصم بارلينا أقوى كود خصم 30% على جميع المنتجات</h3>
                
                <!-- Button moved inside but only shows on desktop -->
<!-- Desktop coupon button -->
<div class="button-container hidden md:block">
    <button class="button">
        <span class="code-text">he**</span>
        <span class="layer">عرض الكوبون</span>
    </button>
</div>

            </div>
        </div>

<!-- Mobile coupon button -->
<div class="flex justify-end mt-0 mb-2 md:hidden">
    <div class="button-container">
        <button class="button">
            <span class="code-text">he**</span>
            <span class="layer">عرض الكوبون</span>
        </button>
    </div>
</div>

        <hr class="border-gray-200 mb-4"/>

        <!-- Footer section -->
        <div class="flex items-center justify-between text-sm text-gray-600 w-full">
            <button class="details-button flex items-center gap-2 hover:text-blue-600" onclick="toggleDetails(this)">
                <svg class="w-4 md:w-5 h-4 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
                التفاصيل
            </button>

            <div class="flex items-center gap-4 md:gap-6">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    2 مستخدم
                </span>
<!-- Share button -->
<button class="share-button flex items-center gap-2 hover:text-blue-600" onclick="openShareModal(1)">
    <svg class="w-4 md:w-5 h-4 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
    </svg>
    مشاركة
</button>
            </div>
        </div>

        <!-- Collapsible Details Content -->
        <div class="details-content mt-4 bg-gray-50 rounded-lg p-4 hidden">
            <div class="text-gray-600 text-sm space-y-2">
                <p>مثال نص مولد لاي كلام </p>
            </div>
        </div>
    </div>
</div>




                </div>

                <aside class="w-full lg:w-60 mt-4">

                
                <div class="bg-white rounded-xl shadow-sm border p-4 space-y-2 stats-mobile-hidden">
                <div class="stats-item flex items-center justify-between p-3 rounded-lg">
                <div class="flex items-center gap-2 text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>مستخدم اليوم</span>
                </div>
                <span class="text-lg font-medium">5</span>
            </div>

            <div class="stats-item flex items-center justify-between p-3 rounded-lg">
                <div class="flex items-center gap-2 text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    <span>الكوبونات</span>
                </div>
                <span class="text-lg font-medium">3</span>
            </div>

            <div class="stats-item flex items-center justify-between p-3 rounded-lg">
                <div class="flex items-center gap-2 text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                    </svg>
                    <span>العروض</span>
                </div>
                <span class="text-lg font-medium">3</span>
            </div>
        </div>
        <br>
        <div class="bg-white rounded-xl shadow-sm border p-4 space-y-2">
            <div class="text-center mb-4">
                <h3 class="text-xl font-bold mb-4">التقييمات</h3>
                <div class="flex justify-center gap-1 mb-2">
                    <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-600">صوّت 6665 عميلاً بمتوسط 4 نجوم</p>
            </div>
        </div>

        <br>

    </aside>
            </div>


    <!-- Add after coupon section, in same container -->
    <div class="bg-white rounded-xl shadow-sm border p-6 mt-8">
        <h2 class="text-xl font-bold mb-6">تفاصيل كود خصم بارلينا</h2>
        
        <article class="prose prose-sm max-w-none">
            <p class="text-gray-600 mb-4 leading-relaxed">
                متجر بارلينا يقدم تشكيلة واسعة من الفساتين النسائية والعبايات العصرية. يمكنك الآن الاستفادة من خصم 30% على جميع المنتجات من خلال كود الخصم المقدم.
            </p>
        </article>
    </div>
        </main>













<!-- Start Modal -->

<!-- Add this modal HTML right before closing body tag -->
<div id="couponModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-start sm:items-center justify-center overflow-y-auto p-4 sm:p-6">
    <div class="bg-white rounded-xl w-full max-w-xl mx-auto relative transform transition-all mt-16 sm:mt-0">
        <!-- Close button -->
        <button onclick="closeModal()" class="absolute left-2 top-2 sm:left-4 sm:top-4 text-gray-400 hover:text-gray-600 transition-colors p-2">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <!-- Header -->
        <div class="bg-gray-50 p-4 sm:p-6 rounded-t-xl border-b">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-4">
                <img src="https://couponku.com/wp-content/uploads/2024/10/%D9%83%D9%88%D8%AF-%D8%AE%D8%B5%D9%85-%D9%81%D8%B3%D8%A7%D8%AA%D9%8A%D9%86-%D8%A8%D8%A7%D8%B1%D9%84%D9%8A%D9%86%D8%A7.png" alt="Barlina" class="h-20 sm:h-15 order-1 sm:order-none"/>
                <h2 class="text-lg sm:text-xl font-bold text-center sm:text-right flex-1" id="modalTitle">كود barlina خصم جبار يصل إلى 30% على أشيك افساتين</h2>
            </div>
        </div>

        <!-- Content -->
        <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
            <!-- Copy instruction -->
            <div class="text-center">
                <p class="text-sm text-gray-600">انسخ والصق هذا الكود في 
                    <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">بارلينا</a>
                </p>
            </div>

            <!-- Coupon display and copy section -->
            <div class="relative">
                <!-- Mobile layout (stack) -->
                <div class="block sm:hidden space-y-3">
                    <div class="bg-gray-50 p-4 rounded-lg text-center border-2 border-dashed border-gray-200">
                        <span id="couponCodeMobile" class="font-mono text-lg font-bold">HELLO30</span>
                    </div>
                    <button onclick="copyCoupon()" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg flex items-center justify-center gap-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                        نسخ الكود
                    </button>
                </div>

                <!-- Desktop layout (side by side) -->
                <div class="hidden sm:flex items-stretch rounded-lg overflow-hidden border-2 border-dashed border-gray-200">
                    <div class="flex-1 bg-gray-50 p-4 text-center font-mono text-lg font-bold">
                        <span id="couponCodeDesktop">HELLO30</span>
                    </div>
                    <button onclick="copyCoupon()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 flex items-center justify-center gap-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                        نسخ
                    </button>
                </div>
            </div>

            <!-- Verification and expiry -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-6 text-sm">
                <div class="flex items-center gap-2 text-green-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="font-medium">محقّق</span>
                </div>
                <div class="flex items-center gap-2 text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-xs sm:text-sm">صالح حتي السبت 30, نوفمبر 2024</span>
                </div>
            </div>

            <!-- Success message -->
            <div id="copySuccess" class="hidden fixed bottom-4 left-4 right-4 sm:relative sm:bottom-auto sm:left-auto sm:right-auto">
                <div class="bg-green-50 text-green-600 px-4 py-3 rounded-lg text-sm text-center shadow-lg sm:shadow-none">
                    تم نسخ الكود بنجاح!
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes slideUp {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}

@keyframes modalFade {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

.modal-animate-in {
    animation: modalFade 0.2s ease-out;
}

/* Toast animation for mobile */
.copy-success-animate {
    animation: slideUp 0.3s ease-out;
}

/* Improved touch targets for mobile */
@media (max-width: 640px) {
    button {
        min-height: 0px; /* Better touch targets */
    }
    
    .modal-content {
        margin-bottom: 20px; /* Space for bottom message */
    }
}
</style>





<script>
// Coupons data
const coupons = {
    '1': { 
        code: 'HELLO30', 
        title: 'كود barlina خصم جبار يصل إلى 30% على أشيك افساتين'
    },
    '2': { 
        code: 'SAVE25', 
        title: 'كود خصم barllina خصم يصل إلى 25% على جميع المنتجات'
    }
};

// Current coupon tracking
let currentCouponId = null;

// Initialize all event listeners when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize coupon buttons
    const couponButtons = document.querySelectorAll('.button');
    couponButtons.forEach((button, index) => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            openModal(index + 1);
        });
    });

    // Initialize share buttons
    document.querySelectorAll('.coupon-card').forEach((card, index) => {
        const shareButton = card.querySelector('.share-button');
        if (shareButton) {
            shareButton.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                openShareModal(index + 1);
            });
        }
    });

    // Initialize modal close events
    initializeModalEvents();
});

// Coupon Modal Functions
function openModal(couponId) {
    const modal = document.getElementById('couponModal');
    const coupon = coupons[couponId];
    
    if (coupon) {
        document.getElementById('modalTitle').textContent = coupon.title;
        document.getElementById('couponCodeMobile').textContent = coupon.code;
        document.getElementById('couponCodeDesktop').textContent = coupon.code;
        modal.classList.remove('hidden');
        modal.classList.add('flex', 'modal-animate-in');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal() {
    const modal = document.getElementById('couponModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

function copyCoupon() {
    const couponCode = document.getElementById('couponCodeDesktop').textContent;
    navigator.clipboard.writeText(couponCode).then(() => {
        const successMsg = document.getElementById('copySuccess');
        successMsg.classList.remove('hidden');
        successMsg.classList.add('copy-success-animate');
        
        setTimeout(() => {
            successMsg.classList.add('hidden');
            successMsg.classList.remove('copy-success-animate');
        }, 2000);
    });
}

// Share Modal Functions
function openShareModal(couponId) {
    currentCouponId = couponId;
    const shareUrl = `${window.location.origin}${window.location.pathname}#${couponId}`;
    const modal = document.getElementById('shareModal');
    document.getElementById('shareUrl').textContent = shareUrl;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeShareModal() {
    const modal = document.getElementById('shareModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

function copyShareLink() {
    const shareUrl = document.getElementById('shareUrl').textContent;
    navigator.clipboard.writeText(shareUrl).then(() => {
        const successMsg = document.getElementById('shareCopySuccess');
        successMsg.classList.remove('hidden');
        setTimeout(() => {
            successMsg.classList.add('hidden');
        }, 2000);
    });
}

// Share Functions
function shareOnWhatsApp() {
    const shareUrl = document.getElementById('shareUrl').textContent;
    const coupon = coupons[currentCouponId];
    const text = `${coupon.title}\n${shareUrl}`;
    window.open(`https://wa.me/?text=${encodeURIComponent(text)}`, '_blank');
}

function shareOnTelegram() {
    const shareUrl = document.getElementById('shareUrl').textContent;
    const coupon = coupons[currentCouponId];
    const text = `${coupon.title}\n${shareUrl}`;
    window.open(`https://t.me/share/url?url=${encodeURIComponent(shareUrl)}&text=${encodeURIComponent(coupon.title)}`, '_blank');
}

function shareOnTwitter() {
    const shareUrl = document.getElementById('shareUrl').textContent;
    const coupon = coupons[currentCouponId];
    window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(coupon.title)}&url=${encodeURIComponent(shareUrl)}`, '_blank');
}

// Details Toggle Function
function toggleDetails(button) {
    const detailsContent = button.closest('.coupon-card').querySelector('.details-content');
    const svg = button.querySelector('svg');
    
    detailsContent.classList.toggle('hidden');
    
    if (detailsContent.classList.contains('hidden')) {
        svg.style.transform = 'rotate(0deg)';
    } else {
        svg.style.transform = 'rotate(180deg)';
    }
}

// Initialize Modal Events
function initializeModalEvents() {
    // Close modals when clicking outside
    document.getElementById('couponModal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('couponModal')) {
            closeModal();
        }
    });

    document.getElementById('shareModal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('shareModal')) {
            closeShareModal();
        }
    });

    // ESC key handler
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal();
            closeShareModal();
        }
    });
}
</script>

<!-- End Modal -->






<!-- Details Button  -->

<script>
function toggleDetails(button) {
    const detailsContent = button.closest('.coupon-card').querySelector('.details-content');
    const svg = button.querySelector('svg');
    
    // Toggle visibility
    detailsContent.classList.toggle('hidden');
    
    // Rotate arrow
    if (detailsContent.classList.contains('hidden')) {
        svg.style.transform = 'rotate(0deg)';
    } else {
        svg.style.transform = 'rotate(180deg)';
    }
}
</script>

<!-- End Details Button -->




<!-- Share Modal -->
 <!-- Add this share modal HTML right before the closing body tag -->
<div id="shareModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-md mx-auto relative">
        <!-- Close button -->
        <button onclick="closeShareModal()" class="absolute left-2 top-2 text-gray-400 hover:text-gray-600 p-2">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <!-- Header -->
        <div class="p-4 border-b text-center">
            <h3 class="text-lg font-bold">مشاركة الكوبون</h3>
        </div>

        <!-- Content -->
        <div class="p-4 space-y-4">
            <!-- URL display and copy section -->
            <div class="relative">
                <div class="bg-gray-50 p-3 rounded-lg text-left border-2 border-dashed border-gray-200 mb-3">
                    <span id="shareUrl" class="text-sm text-gray-600 break-all"></span>
                </div>
                <button onclick="copyShareLink()" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                    </svg>
                    نسخ الرابط
                </button>
            </div>

            <!-- Social share buttons -->
            <div class="flex justify-center gap-4">
                <button onclick="shareOnWhatsApp()" class="p-2 rounded-full bg-green-500 hover:bg-green-600 text-white">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM15.85 15.85L14.5 16.5L9.5 11.5V7.5H11V10.8L15.3 15.1L15.85 15.85Z"/>
                    </svg>
                </button>
                <button onclick="shareOnTelegram()" class="p-2 rounded-full bg-blue-500 hover:bg-blue-600 text-white">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7L12 12L22 7L12 2Z"/>
                        <path d="M2 17L12 22L22 17"/>
                        <path d="M2 12L12 17L22 12"/>
                    </svg>
                </button>
                <button onclick="shareOnTwitter()" class="p-2 rounded-full bg-blue-400 hover:bg-blue-500 text-white">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>
                    </svg>test.php
                </button>
            </div>

            <!-- Copy success message -->
            <div id="shareCopySuccess" class="hidden">
                <div class="bg-green-50 text-green-600 px-4 py-3 rounded-lg text-sm text-center">
                    تم نسخ الرابط بنجاح!
                </div>
            </div>
        </div>
    </div>
</div>


    </body>
    </html>
