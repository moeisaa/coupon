<?php
session_start();

include('db_connect.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دليل تركيب سكربت الكوبونات</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        h2 {
            color: #e67e22;
            margin-top: 25px;
        }
        .step {
            background: #f9f9f9;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border-right: 4px solid #3498db;
        }
        .code-block {
            background: #2c3e50;
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            direction: ltr;
            text-align: left;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .critical {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border-right: 4px solid #dc3545;
        }
        .important {
            background: #e8f5e9;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border-right: 4px solid #28a745;
        }
        code {
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 3px;
            color: #e83e8c;
        }
        .file-path {
            background: #e9ecef;
            padding: 5px 10px;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>الدليل النهائي لتركيب سكربت الكوبونات</h1>
        
        <div class="critical">
            <strong>ملفات السكربت المطلوبة:</strong>
            <ul>
                <li>ملف مضغوط ZIP يحتوي على السكربت كاملاً</li>
                <li>ملف SQL لقاعدة البيانات</li>
            </ul>
        </div>

        <h2>أولاً: إعداد قاعدة البيانات</h2>
        <div class="step">
            1. إنشاء قاعدة بيانات جديدة (Create Database)<br>
            2. استيراد ملف SQL المرفق مع السكربت (Import SQL File)<br>
            3. احفظ معلومات الاتصال التالية:
            <ul>
                <li>اسم قاعدة البيانات</li>
                <li>اسم المستخدم</li>
                <li>كلمة المرور</li>
                <li>اسم المضيف (عادةً localhost)</li>
            </ul>
        </div>

        <h2>ثانياً: رفع وفك ضغط الملفات</h2>
        <div class="step">
            1. ادخل إلى لوحة التحكم cPanel<br>
            2. ارفع ملف ZIP إلى المسار المطلوب<br>
            3. قم بفك الضغط في نفس المسار<br>
            4. تأكد من وجود جميع الملفات في المكان الصحيح
        </div>

        <h2>ثالثاً: تعديل ملفات الاتصال بقاعدة البيانات</h2>
        <div class="important">
            يجب تعديل معلومات الاتصال في الملفات التالية:
            <ul>
                <li class="file-path">login.php</li>
                <li class="file-path">display_page.php</li>
                <li class="file-path">db_Connect.php</li>
            </ul>
        </div>

        <div class="code-block">
            $servername = "localhost";<br>
            $username = "اسم_المستخدم";<br>
            $password = "كلمة_المرور";<br>
            $dbname = "اسم_قاعدة_البيانات";
        </div>

        <h2>رابعاً: إعداد روابط الدومين</h2>
        <div class="step">
            <strong>1. تعديل مسار الدومين في admin.php:</strong><br>
            - افتح ملف <code>admin.php</code><br>
            - اذهب إلى السطر 274<br>
            - قم بتحديث مسار الدومين<br><br>
            
            <strong>2. تعديل إعادة التوجيه في display_page.php:</strong><br>
            - افتح ملف <code>display_page.php</code><br>
            - اذهب إلى السطر 57<br>
            - قم بتحديث مسار إعادة التوجيه<br><br>
            
            <strong>3. مراجعة index.php:</strong><br>
            - تأكد من تحديث جميع مسارات الروابط
        </div>

        <h2>خامساً: تعديل الشعار (اللوجو)</h2>
        <div class="step">
            في ملف <code>display_page.php</code> (theme1 فقط):
            <ul>
                <li>قم بتعديل السطر 362</li>
                <li>قم بتعديل السطر 344</li>
            </ul>
        </div>

        <h2>سادساً: التحقق من ملف .htaccess</h2>
        <div class="step">
            1. تأكد من وجود ملف .htaccess<br>
            2. تأكد من صلاحيات التعديل عليه<br>
            3. تحقق من صحة الأكواد الخاصة بالتوجيه
        </div>

        <div class="warning">
            <strong>قائمة التحقق النهائية:</strong>
            <ul>
                <li>✓ تم تعديل ملفات الاتصال الثلاثة بقاعدة البيانات</li>
                <li>✓ تم تعديل مسار الدومين في admin.php</li>
                <li>✓ تم تعديل التوجيه في display_page.php</li>
                <li>✓ تم تحديث الروابط في index.php</li>
                <li>✓ تم تعديل الشعار في theme1</li>
                <li>✓ تم التحقق من ملف .htaccess</li>
            </ul>
        </div>

        <div class="critical">
            <strong>تنبيهات هامة:</strong>
            <ul>
                <li>احتفظ دائماً بنسخة احتياطية من جميع الملفات</li>
                <li>تأكد من صلاحيات المجلدات (755) والملفات (644)</li>
                <li>اختبر عمل السكربت قبل إطلاقه للمستخدمين</li>
                <li>تأكد من عمل جميع روابط التوجيه</li>
            </ul>
        </div>

        <div class="important">
            <strong>المشاكل الشائعة وحلولها:</strong>
            <ul>
                <li>خطأ في الاتصال بقاعدة البيانات: راجع بيانات الاتصال في الملفات الثلاثة</li>
                <li>مشكلة في التوجيه: تحقق من السطر 57 في display_page.php</li>
                <li>عدم ظهور الشعار: راجع السطرين 362 و 344 في theme1</li>
                <li>أخطاء 404: تأكد من صحة ملف .htaccess</li>
            </ul>
        </div>
    </div>

    <div style="text-align: center; margin-top: 50px; padding-top: 20px; border-top: 1px solid #eee;">
        <p style="color: #666; font-size: 14px;">
            تم إعداد هذا الدليل بواسطة محمود جلال
            <br>
            &copy; 2024 جميع الحقوق محفوظة
        </p>
    </div>
</body>
</html>