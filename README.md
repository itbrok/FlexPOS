<p  dir="rtl">
🇮🇶 **هذا المشروع عراقي، ومجاني للجميع، ومتاح للاستخدام بأي شكل من الأشكال** 🔓✨

تحت مبدأ **المعرفة المفتوحة** 📚، نسعى إلى **دعم المجتمع** 🤝 من خلال إتاحة هذا المشروع دون قيود.
يمكنك استخدامه، تعديله، أو إعادة نشره بحرية تامة 🔄🛠️📤

---
🚨 مساعدة عاجلة مطلوبة! 🚨  

أرجو المساعدة — أبحث عن مساهمين للانضمام إلى هذا المشروع.  
كل أنواع المساعدة مرحب بها، سواء في البرمجة أو التصميم أو التوثيق أو الاختبار.  
إذا كنت مهتماً، يرجى التواصل أو فتح طلب سحب (Pull Request)!


# **Flex POS**

نظام Flex POS هو تطبيق بسيط وخفيف لإدارة نقاط البيع والمخزون، مبني باستخدام PHP. يشمل لوحة تحكم إدارية، وإدارة العملاء والطلبات، وإمكانية طباعة الإيصالات باستخدام قوالب قابلة للتخصيص.

> **ملاحظة**: هذا المشروع **عراقي بالكامل**، مجاني 100%، مفتوح المصدر، ومتاح للجميع للتعديل، الإضافة أو الاستخدام بأي طريقة يرونها مناسبة.

---
## **الميزات**

* إدارة المنتجات مع إمكانية البحث باستخدام الباركود.
* تتبع العملاء والطلبات والديون المستحقة.
* طباعة إيصالات البيع من خلال طابعة قابلة للتكوين.
* حسابات مستخدمين بصلاحيات محددة، مع لوحة تحكم إدارية.
* جرس تنبيهات يعرض عدد الطلبات الأخيرة. (غير مكتمل)
* إمكانية رفع شعار مخصص من لوحة الإعدادات.

---
## **المتطلبات الأساسية**

* تم انشاء واختبار هذا التطبيق على Xampp فقط
* PHP 7.4 أو أحدث مع امتداد MySQLi.
* خادم قاعدة بيانات MySQL.
* خادم ويب مثل Apache أو Nginx.

---
## **التهيئة**

توجد كل الإعدادات في ملف `config.php`. يمكنك تعديل بيانات الاتصال بقاعدة البيانات. يمكن أيضاً تعديل إعدادات الطابعة وغيرها من لوحة التحكم بعد تسجيل الدخول.

---
## **التثبيت**
_لتثبيت التطبيق:_
### **اعداد البيئة**

* عند تشغيل <a href="https://www.apachefriends.org/index.html" target="_blank">XAMPP</a>

<img width="668" height="433" alt="image" src="https://github.com/user-attachments/assets/eeeefc2a-0a0c-4034-9c26-108c98692768" />

* ثبت Apache Service و MySQL Service من خلال النقر على <img width="23" height="25" alt="image" src="https://github.com/user-attachments/assets/8b2b35a1-1066-4e9b-9f14-3e16aed31690" />

* يجب ان تكون الخدمات مثبتة وعندها ستظهر <img width="24" height="25" alt="image" src="https://github.com/user-attachments/assets/bff2bbd8-c624-44e2-a21f-1a759772147f" />

  <img width="666" height="434" alt="image" src="https://github.com/user-attachments/assets/4cb45da0-026d-4608-9eab-f519ea7e2430" />

* توجه الى Services من خلال النقر على <img width="88" height="29" alt="image" src="https://github.com/user-attachments/assets/c4de97a2-0263-46eb-8255-9b4fe55b7100" />

<img width="988" height="590" alt="image" src="https://github.com/user-attachments/assets/7d24a108-1f3d-48af-9d07-fabd501bac04" />

* ابحث عن Apache Service <img width="89" height="22" alt="image" src="https://github.com/user-attachments/assets/3f4bd3c9-f664-4fb6-bbe1-f693abe62d5f" /> وانقر نقرتين عليها

<img width="402" height="466" alt="image" src="https://github.com/user-attachments/assets/ea8e97b0-e022-4ca7-b2fd-6e90d92d0cb6" />

* توجه الى <img width="44" height="15" alt="image" src="https://github.com/user-attachments/assets/b6565c63-c59d-4455-957f-98a1f1c8d3d1" /> وفعل خيار <img width="198" height="25" alt="image" src="https://github.com/user-attachments/assets/6b019880-90df-4e67-bcb4-30c4431b1e0e" /> ثم اختر OK

<img width="405" height="466" alt="image" src="https://github.com/user-attachments/assets/67c36ce6-fa40-4676-adb4-82e1466b2115" />

* قم بتشغيل Apache و MySQL من خلال النقر على <img width="62" height="25" alt="image" src="https://github.com/user-attachments/assets/e73e4a85-6f65-4a52-ae05-4fcf299f48f2" />

<img width="668" height="431" alt="image" src="https://github.com/user-attachments/assets/26dd4bf7-ddca-4cf0-9443-f0cd517ca775" />


### **طريقة التثبيت**
1. استخرج الملفات داخل مجلد `C:\xampp\htdocs\`.
2. انتقل الى `http://localhost/FlexPOS-main` في المتصفح واملأ النموذج ببيانات قاعدة البيانات.
3. تهانينا لقد قمت بتثبيت التطبيق 
---
## **الاستخدام**

* قم بتسجيل الدخول باستخدام حساب المدير اسم المستخدم ```admin``` كلمة المرور ```admin```.
* من لوحة التحكم، أضف المنتجات والعملاء والمستخدمين وعدّل الإعدادات.
* استخدم شاشة المبيعات لإنشاء الطلبات وطباعة الفواتير.

---
## **تخصيص قالب الطباعة**
ملف HTML الخاص بطباعة الإيصالات موجود في `print/template/printtemplate.txt`.

---
## **الرخصة**

هذا المشروع مرخّص تحت [رخصة MIT](LICENSE) — حرّ بالكامل ومتاح للجميع.

---
</p>
