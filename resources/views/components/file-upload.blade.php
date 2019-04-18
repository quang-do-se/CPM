<div class="card">
    <div class="card-header">{{ $title }}</div>

    <div class="card-body">
        <form action="{{ $action }}" method="POST"
              enctype="multipart/form-data">
            @csrf
            <div>
                <input type="file" name="upload" class="form-control" required>

                <div class="custom-control custom-checkbox" style="margin-top: 10px">
                    <input type="checkbox" class="custom-control-input" id="has-header" name="has-header">
                    <label class="custom-control-label" for="has-header">Has Header?</label>
                </div>

                <input type="submit" class="btn btn-primary" id="save" style="margin-top: 10px">
            </div>
        </form>

        <div id="message" class="clearfix" style="margin-top: 10px">
            @include('flash::message')
        </div>
    </div>
</div>
