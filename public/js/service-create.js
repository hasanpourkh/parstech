document.addEventListener('DOMContentLoaded', function () {
    // تولید خودکار کد خدمات (همانند قبل)
    const codeInput = document.getElementById('service_code');
    const customSwitch = document.getElementById('custom_code_switch');
    let baseCode = '';
    function fetchNextCode() {
        fetch('/services/next-code')
            .then(res => res.json())
            .then(data => {
                baseCode = data.code || '';
                codeInput.value = baseCode;
                codeInput.readOnly = true;
            });
    }
    fetchNextCode();
    customSwitch.addEventListener('change', function() {
        if(customSwitch.checked) {
            codeInput.readOnly = false;
            codeInput.value = '';
            codeInput.focus();
        } else {
            fetchNextCode();
        }
    });

    // پیش‌نمایش تصویر خدمت
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image_preview');
    if(imageInput && imagePreview){
        imageInput.addEventListener('change', function() {
            if (imageInput.files && imageInput.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.style.display = "block";
                    imagePreview.src = e.target.result;
                }
                reader.readAsDataURL(imageInput.files[0]);
            }
        });
    }

    // مدیریت لیست واحدها در مودال
    const addUnitBtn = document.getElementById('add-unit-btn');
    const addUnitModal = document.getElementById('addUnitModal');
    const addUnitForm = document.getElementById('add-unit-form');
    const newUnitInput = document.getElementById('new-unit-input');
    const editUnitIdInput = document.getElementById('edit-unit-id');
    const unitSelect = document.getElementById('unit_id');
    const unitModalList = document.getElementById('unit-modal-list');

    // همزمان با باز شدن مودال، لیست واحدها را نمایش بده
    if(addUnitBtn && addUnitModal && addUnitForm && newUnitInput && editUnitIdInput && unitSelect && unitModalList){
        addUnitBtn.addEventListener('click', function() {
            newUnitInput.value = '';
            editUnitIdInput.value = '';
            // لیست واحدها را از سلکت اصلی استخراج کن
            unitModalList.innerHTML = '';
            for(let i=0; i<unitSelect.options.length; i++) {
                let opt = unitSelect.options[i];
                if(opt.value === "") continue;
                let li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center p-1';
                li.setAttribute('data-unit-id', opt.value);
                li.innerHTML = `<span class="unit-name">${opt.text}</span>
                <span>
                    <button type="button" class="btn btn-sm btn-outline-danger delete-unit-btn">حذف</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary edit-unit-btn">ویرایش</button>
                </span>`;
                unitModalList.appendChild(li);
            }
            $('#addUnitModal').modal('show');
        });

        // ثبت واحد (افزودن یا ویرایش)
        addUnitForm.addEventListener('submit', function(e) {
            e.preventDefault();
            let newUnit = newUnitInput.value.trim();
            let editUnitId = editUnitIdInput.value;
            if(!newUnit) return;

            // ویرایش
            if(editUnitId) {
                // درخواست ویرایش (اینجا فرض بر این است که api ویرایش داری، وگرنه فقط در DOM)
                for(let i=0; i<unitSelect.options.length; i++) {
                    if(unitSelect.options[i].value === editUnitId) {
                        unitSelect.options[i].text = newUnit;
                        // لیست مودالی هم آپدیت می‌شود
                        let li = unitModalList.querySelector('li[data-unit-id="'+editUnitId+'"]');
                        if(li) li.querySelector('.unit-name').innerText = newUnit;
                        break;
                    }
                }
                $('#addUnitModal').modal('hide');
                return;
            }

            // افزودن
            // بررسی وجود قبلی
            let exists = false;
            for(let i=0; i<unitSelect.options.length; i++) {
                if(unitSelect.options[i].text === newUnit) exists = true;
            }
            if(!exists) {
                // درخواست به سرور
                fetch('/units', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ title: newUnit })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success && data.unit){
                        // افزودن به سلکت
                        let opt = document.createElement('option');
                        opt.value = data.unit.id;
                        opt.text = data.unit.title;
                        unitSelect.appendChild(opt);
                        unitSelect.value = data.unit.id;
                        // افزودن به مودال لیست
                        let li = document.createElement('li');
                        li.className = 'list-group-item d-flex justify-content-between align-items-center p-1';
                        li.setAttribute('data-unit-id', data.unit.id);
                        li.innerHTML = `<span class="unit-name">${data.unit.title}</span>
                        <span>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-unit-btn">حذف</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary edit-unit-btn">ویرایش</button>
                        </span>`;
                        unitModalList.appendChild(li);
                        $('#addUnitModal').modal('hide');
                    }else{
                        alert('خطا در ثبت واحد!');
                    }
                })
                .catch(() => alert('خطا در ارتباط با سرور!'));
            }else{
                alert('این واحد قبلا وجود دارد.');
            }
        });

        // مدیریت حذف و ویرایش در لیست داخل مودال
        unitModalList.addEventListener('click', function(e) {
            let li = e.target.closest('li');
            if(!li) return;
            let unitId = li.getAttribute('data-unit-id');
            let unitName = li.querySelector('.unit-name').innerText;
            // حذف
            if(e.target.classList.contains('delete-unit-btn')) {
                if(confirm('حذف واحد "' + unitName + '"؟')) {
                    // فرض بر این است که api حذف داری، وگرنه فقط از DOM
                    // حذف از سلکت و DOM
                    for(let i=0; i<unitSelect.options.length; i++) {
                        if(unitSelect.options[i].value === unitId) {
                            unitSelect.remove(i);
                            break;
                        }
                    }
                    li.remove();
                }
            }
            // ویرایش
            if(e.target.classList.contains('edit-unit-btn')) {
                newUnitInput.value = unitName;
                editUnitIdInput.value = unitId;
                newUnitInput.focus();
            }
        });
    }
});
