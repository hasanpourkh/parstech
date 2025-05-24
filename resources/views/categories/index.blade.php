@extends('layouts.app')

@section('title', 'دسته‌بندی‌ها')

@section('content')
<div class="container my-5">
    <div class="card shadow-lg">
        <div class="card-header bg-gradient-primary text-white">
            <h4 class="mb-0">دسته‌بندی‌ها</h4>
        </div>
        <div class="card-body">
            <div id="categories-table"></div>
        </div>
    </div>
</div>
<!-- Tabulator CSS & JS -->
<link href="https://unpkg.com/tabulator-tables@5.5.2/dist/css/tabulator.min.css" rel="stylesheet">
<script src="https://unpkg.com/tabulator-tables@5.5.2/dist/js/tabulator.min.js"></script>
<!-- استایل سفارشی -->
<style>
    .tabulator {
        font-family: Vazirmatn, Tahoma, Arial;
        font-size: 15px;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    }
    .tabulator .tabulator-header {
        background: linear-gradient(90deg, #00c6ff 0%, #0072ff 100%);
        color: #fff;
        font-weight: bold;
    }
    .tabulator-row.tabulator-tree-branch {
        background: #f4f8ff;
    }
    .tabulator-row.tabulator-selected {
        background: #e2f0fb;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tableData = @json($tree);

    var table = new Tabulator("#categories-table", {
        data: tableData,
        height: "600px",
        layout:"fitColumns",
        movableColumns: true,
        resizableRows: true,
        placeholder: "دسته‌ای وجود ندارد.",
        selectable: 1,
        columns:[
            {title:"نام دسته", field:"name", width:260, headerSort:false, responsive:0, formatter:function(cell){
                return `<span style="font-weight:500">${cell.getValue()}</span>`;
            }, headerFilter:"input"},
            {title:"نوع", field:"type", width:140, headerSort:false, formatter:function(cell){
                let type = cell.getValue();
                if(type === 'product') return '<span class="badge bg-primary">محصول</span>';
                if(type === 'service') return '<span class="badge bg-success">خدمت</span>';
                return type;
            }},
            {title:"تاریخ ایجاد", field:"created_at", width:120, align:"center", headerSort:false},
            {title:"عملیات", field:"id", width:120, align:"center", formatter:function(cell){
                let id = cell.getValue();
                return `<a href="/categories/${id}/edit" class="btn btn-sm btn-outline-primary mx-1">ویرایش</a>
                        <a href="/categories/${id}/delete" onclick="return confirm('حذف شود؟')" class="btn btn-sm btn-outline-danger">حذف</a>`;
            }, headerSort:false}
        ],
        // فعال‌سازی حالت درختی
        dataTree: true,
        dataTreeStartExpanded: false,
        dataTreeChildField: "_children",
        locale:true,
        langs:{
            "fa-fa":{
                "columns":{
                    "name":"نام دسته",
                    "type":"نوع",
                    "created_at":"تاریخ ایجاد",
                    "id":"عملیات"
                },
                "data":{
                    "loading":"در حال بارگذاری...",
                    "error":"خطا در دریافت داده‌ها"
                },
                "pagination":{
                    "first":"ابتدا",
                    "first_title":"صفحه اول",
                    "last":"انتها",
                    "last_title":"صفحه آخر",
                    "prev":"قبلی",
                    "prev_title":"صفحه قبلی",
                    "next":"بعدی",
                    "next_title":"صفحه بعدی"
                }
            }
        }
    });
});
</script>
@endsection
