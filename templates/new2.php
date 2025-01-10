<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>كوبونكو - تصميم جديد</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap');
       /* Tailwind color overrides */
       .bg-white {
            background-color: #FFFFFF !important;
        }

        .bg-gray-50 {
            background-color: #F5F3FF !important;
        }

        .text-blue-600, 
        .hover\:text-blue-600:hover,
        .text-blue-700,
        .hover\:text-blue-700:hover {
            color: #7C3AED !important;
        }

        .bg-blue-600,
        .hover\:bg-blue-700:hover {
            background-color: #7C3AED !important;
        }

        .bg-blue-50,
        .hover\:bg-blue-100:hover {
            background-color: #F3E8FF !important;
        }

        .text-blue-600 {
            color: #7C3AED !important;
        }

        .border-blue-200,
        .hover\:border-blue-200:hover {
            border-color: #DDD6FE !important;
        }

        .shadow-sm {
            box-shadow: 0 4px 15px rgba(124, 58, 237, 0.08) !important;
        }

        .border-gray-200 {
            border-color: #F3E8FF !important;
        }

        /* Star rating colors */
        .text-gray-300 {
            color: #E9D5FF !important;
        }

        /* SVG colors */
        [stroke="#2563eb"] {
            stroke: #7C3AED !important;
        }

        [fill="#E6F4FF"] {
            fill: #F3E8FF !important;
        }

        [fill="#0958D9"] {
            fill: #7C3AED !important;
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
    text-align: left;
    display: block;
    padding-right: 8px;
}

.button .layer {
    position: absolute;
    right: 0;
    top: 0;
    color: #ffffff;
    background: linear-gradient(90deg, #7c3aed,#87378d);
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




/* LTR */
/* LTR Specific Styles */

html[dir="ltr"] {
    direction: ltr;
}

html[dir="ltr"] .text-right {
    text-align: left;
}

html[dir="ltr"] .button {
    text-align: right;
}

html[dir="ltr"] .code-text {
    text-align: left;
    padding-left: 8px;
    padding-right: 0;
}

html[dir="ltr"] .button .layer {
    left: 0;
    right: auto;
    text-align: right;
}

/* Adjust flexbox direction for LTR */
html[dir="ltr"] .flex-row-reverse {
    flex-direction: row;
}

/* Adjust margins and paddings */
html[dir="ltr"] .mr-auto {
    margin-left: auto;
    margin-right: 0;
}

html[dir="ltr"] .ml-auto {
    margin-right: auto;
    margin-left: 0;
}

/* Modal adjustments */
html[dir="ltr"] .modal-close {
    right: 2px;
    left: auto;
}

/* Button animation for LTR */
html[dir="ltr"] .button:hover .layer {
    width: 75%;
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

        <main class="max-w-6xl mx-auto px-4 sm:px-10 py-6">
        <div class="w-full bg-white rounded-xl shadow-sm border mb-2">
   <div class="px-2 sm:px-6 pt-6 pb-2"> 
   <div class="flex flex-col sm:flex-row gap-0 sm:gap-8 items-start">
               <!-- First row: Logo + Text (mobile) -->
           <div class="flex flex-row sm:hidden gap-2 w-full">
           <div class="w-[120px] h-[80px] flex-shrink-0">
                   <img src="https://couponku.com/wp-content/uploads/2024/10/%D9%83%D9%88%D8%AF-%D8%AE%D8%B5%D9%85-%D9%81%D8%B3%D8%A7%D8%AA%D9%8A%D9%86-%D8%A8%D8%A7%D8%B1%D9%84%D9%8A%D9%86%D8%A7.png" 
                       alt="Barlina" 
                       class="w-full h-full object-contain hover-scale"/>
               </div>
               <div class="flex-1">
                   <h1 class="text-base md:text-2xl font-bold mb-3">كود خصم بارلينا 2024: أفضل أكواد خصم barllina لأفضل فساتين</h1>
                   <p class="text-xs md:text-sm mb-4">أفضل أكواد خصم barilina لأفضل فساتين</p>
               </div>
           </div>

           <!-- Desktop layout -->
           <div class="hidden sm:block w-[210px] flex-shrink-0">
               <img src="https://couponku.com/wp-content/uploads/2024/10/%D9%83%D9%88%D8%AF-%D8%AE%D8%B5%D9%85-%D9%81%D8%B3%D8%A7%D8%AA%D9%8A%D9%86-%D8%A8%D8%A7%D8%B1%D9%84%D9%8A%D9%86%D8%A7.png" 
                   alt="Barlina" 
                   class="w-full object-contain hover-scale"/>
           </div>

           <div class="flex-1 text-right w-full">
           <div class="hidden sm:block">
                   <h1 class="text-base md:text-2xl font-bold mb-3">كود خصم بارلينا 2024: أفضل أكواد خصم barllina لأفضل فساتين</h1>
                   <p class="text-xs md:text-sm mb-4">أفضل أكواد خصم barilina لأفضل فساتين</p>
               </div>
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

    </div>
            <div class="flex flex-col lg:flex-row-reverse gap-2 lg:gap-6">
                <div class="flex-1">



                <div class="space-y-6 bg-white mt-4">
                <div class="coupon-card border border-gray-200 hover:border-blue-200 rounded-xl p-2 md:p-4">
                <!-- Main content container -->
                <div class="flex flex-row gap-2 md:gap-4 mb-2 md:mb-4">
   <!-- 30% box - First column -->
   <div class="text-blue-600 text-base md:text-2xl font-bold bg-blue-50 px-2 py-4 md:py-6 rounded-xl w-[70px] md:w-[110px] shrink-0 flex items-center justify-center self-stretch sm:self-auto">
       <div class="text-center leading-[1.5] tracking-tight sm:leading-[1.2]">خصم 30%</div>
   </div>
   
   <!-- Title and button - Second column -->
   <div class="flex-1 flex flex-col sm:flex-row sm:items-center sm:justify-between">
       <div class="space-y-2 sm:space-y-0">
           <h3 class="font-bold text-sm md:text-xl">كوبون خصم بارلينا أقوى كود خصم 30% على جميع المنتجات</h3>
           
           <!-- Mobile button -->
           <div class="button-container sm:hidden flex justify-end">
               <button class="button">
               <span class="layer">عرض الكوبون</span>
                   <span class="code-text">he**</span>
               </button>
           </div>
       </div>

       <!-- Desktop button -->
       <div class="button-container hidden sm:block">
           <button class="button">
           <span class="layer">عرض الكوبون</span>

               <span class="code-text">he**</span>
           </button>
       </div>
   </div>
</div>
        <hr class="border-gray-200 mb-2 sm:mb-4"/>

        <!-- Footer section -->
        <div class="flex items-center justify-between text-sm text-gray-600 w-full">
            <button class="details-button flex items-center gap-2 hover:text-blue-600" onclick="toggleDetails(this)">
                <svg class="w-4 md:w-5 h-4 md:h-5" fill="none" stroke="#2563eb" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
                التفاصيل
            </button>

            <div class="flex items-center gap-4 md:gap-6">
                <span class="flex items-center gap-2">
                <svg width="24" height="24" viewBox="0 0 24 22" fill="none" xmlns="http://www.w3.org/2000/svg">
				<circle cx="12" cy="12" r="12" fill="#E6F4FF"></circle>
				<path d="M14.1745 19.3404H10.1745C6.55448 19.3404 5.00781 17.7937 5.00781 14.1737V10.1737C5.00781 6.55375 6.55448 5.00708 10.1745 5.00708H14.1745C17.7945 5.00708 19.3411 6.55375 19.3411 10.1737V14.1737C19.3411 17.7937 17.7945 19.3404 14.1745 19.3404ZM10.1745 6.00708C7.10115 6.00708 6.00781 7.10041 6.00781 10.1737V14.1737C6.00781 17.2471 7.10115 18.3404 10.1745 18.3404H14.1745C17.2478 18.3404 18.3411 17.2471 18.3411 14.1737V10.1737C18.3411 7.10041 17.2478 6.00708 14.1745 6.00708H10.1745Z" fill="#0958D9"></path>
				<path d="M14.5078 11.1738C13.6811 11.1738 13.0078 10.5005 13.0078 9.67383C13.0078 8.84716 13.6811 8.17383 14.5078 8.17383C15.3345 8.17383 16.0078 8.84716 16.0078 9.67383C16.0078 10.5005 15.3345 11.1738 14.5078 11.1738ZM14.5078 9.17383C14.2345 9.17383 14.0078 9.40049 14.0078 9.67383C14.0078 9.94716 14.2345 10.1738 14.5078 10.1738C14.7811 10.1738 15.0078 9.94716 15.0078 9.67383C15.0078 9.40049 14.7811 9.17383 14.5078 9.17383Z" fill="#0958D9"></path>
				<path d="M9.83984 11.1738C9.01318 11.1738 8.33984 10.5005 8.33984 9.67383C8.33984 8.84716 9.01318 8.17383 9.83984 8.17383C10.6665 8.17383 11.3398 8.84716 11.3398 9.67383C11.3398 10.5005 10.6665 11.1738 9.83984 11.1738ZM9.83984 9.17383C9.56651 9.17383 9.33984 9.40049 9.33984 9.67383C9.33984 9.94716 9.56651 10.1738 9.83984 10.1738C10.1132 10.1738 10.3398 9.94716 10.3398 9.67383C10.3398 9.40049 10.1132 9.17383 9.83984 9.17383Z" fill="#0958D9"></path>
				<path d="M12.1738 17.1405C10.2405 17.1405 8.67383 15.5672 8.67383 13.6405C8.67383 13.0339 9.16716 12.5405 9.77383 12.5405H14.5738C15.1805 12.5405 15.6738 13.0339 15.6738 13.6405C15.6738 15.5672 14.1072 17.1405 12.1738 17.1405ZM9.77383 13.5405C9.72049 13.5405 9.67383 13.5872 9.67383 13.6405C9.67383 15.0205 10.7938 16.1405 12.1738 16.1405C13.5538 16.1405 14.6738 15.0205 14.6738 13.6405C14.6738 13.5872 14.6272 13.5405 14.5738 13.5405H9.77383Z" fill="#0958D9"></path>
			</svg>
                    2 مستخدم
                </span>
<!-- Share button -->
<button class="share-button flex items-center gap-2 hover:text-blue-600" onclick="openShareModal(1)">
    <svg class="w-4 md:w-5 h-4 md:h-5" fill="none" stroke="#2563eb" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
    </svg>
<p class="hidden md:block">مشاركة </p>
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

                <aside class="w-full lg:w-60 mt-0 lg:mt-4">

                
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
        </body>
</html>