<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>فرم‌ساز حرفه‌ای - پارس‌تک</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- استایل فرم‌ساز -->
    <link rel="stylesheet" href="{{ asset('vendor/formbuilder/form-builder.scss') }}">
    <style>
        /* اگر فایل css داشتی این خط رو جایگزین کن:
        <link rel="stylesheet" href="{{ asset('vendor/formbuilder/form-builder.min.css') }}">
        */

        body {
            background: linear-gradient(135deg, #f7fafc 0%, #e3eafc 100%);
            min-height: 100vh;
            margin: 0;
            font-family: 'Vazirmatn', 'IRANSans', Tahoma, Arial, sans-serif;
        }
        .formbuilder-container {
            max-width: 1100px;
            margin: 36px auto 0 auto;
            padding: 32px;
            background: #fff;
            border-radius: 22px;
            box-shadow: 0 8px 36px 0 #0002;
        }
        .formbuilder-header {
            text-align: center;
            margin-bottom: 32px;
        }
        .formbuilder-header h1 {
            font-size: 2.2em;
            margin-bottom: 10px;
            color: #23408c;
            font-weight: bold;
            letter-spacing: 0.04em;
        }
        .formbuilder-header p {
            color: #4f6072;
            font-size: 1.1em;
            margin: 0;
        }
        #fb-editor {
            direction: rtl !important;
            min-height: 520px;
            background: #f5f8fa;
            border-radius: 14px;
            padding: 16px;
            border: 1px solid #e1e9f0;
            transition: box-shadow 0.2s;
        }
        .formbuilder-footer {
            text-align: center;
            margin-top: 35px;
        }
        .btn-save-form {
            background: linear-gradient(90deg, #23408c 0%, #3b82f6 100%);
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 12px 38px;
            font-size: 1.25em;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 2px 8px #3b82f633;
            transition: background 0.2s, box-shadow 0.2s;
        }
        .btn-save-form:hover {
            background: linear-gradient(90deg, #1e2549 0%, #2563eb 100%);
            box-shadow: 0 4px 16px #23408c33;
        }
        @media (max-width: 600px) {
            .formbuilder-container {
                max-width: 96vw;
                padding: 8px;
            }
            .formbuilder-header h1 { font-size: 1.1em; }
        }
    </style>
</head>
<body>
    <div class="formbuilder-container">
        <div class="formbuilder-header">
            <h1>فرم‌ساز پیشرفته پارس‌تک</h1>
            <p>به راحتی فرم دلخواه خود را با Drag & Drop بسازید و ذخیره کنید.</p>
        </div>
        <div id="fb-editor"></div>
        <div class="formbuilder-footer">
            <button id="save-form" class="btn-save-form">ذخیره فرم</button>
        </div>
    </div>

    <!-- اسکریپت‌های لازم -->
    <script src="{{ asset('vendor/formbuilder/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('vendor/formbuilder/form-builder.js') }}"></script>
    <script>
        $(function() {
            var formBuilder = $('#fb-editor').formBuilder({
                disableFields: ['autocomplete', 'hidden'],
                i18n: {
                    locale: 'fa'
                }
            });

            $('#save-form').click(function() {
                var formData = formBuilder.actions.getData('json');
                // نمایش خروجی برای تست
                alert('خروجی فرم (JSON):\n' + formData);
                // اگر خواستی با ajax ذخیره کنی اینجا باید کد بزنی
                // $.post('/your-save-url', { form: formData, _token: '{{ csrf_token() }}' });
            });
        });
    </script>
</body>
</html>
