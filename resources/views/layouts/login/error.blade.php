@if (count($errors))
    <section class="row" style="margin-bottom: 10px;">
        <div class="col-md-12" style="color: red; text-align: center;">

            {{ implode("<br/>", $errors->all()) }}

        </div>
    </section>
@endif