<div class="box box-primary box-solid file-upload">
    <div class="box-header">
        <h3 class="box-title">Картинки галереи</h3>
    </div>
    <div class="box-body" id="galleryjs">
        <div id="multi" class="btn btn-success" data-url="{{url('/admin/products/gallery')}}"
        data-name="multi">ЗАгрузить</div>
        <div class="multi">

        </div>
        <p>
            <small>Вы можете загрузить по очереди любое кол-во.</small><br>
            <small>Рекомендуемые размеры 700х1000</small>
        </p>
    </div>
    {{--my.css .overlay()--}}
    <div class="overlay">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
</div>
