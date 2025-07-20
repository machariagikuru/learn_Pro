@foreach ($courses as $courseItem)
    <div class="col-md-4 mb-4">
        <div class="card card-data p-3 d-flex flex-column h-100">
            <span class="text-primary">{{ $courseItem->title }}</span>
            <a href="{{ route('incourse', $courseItem->id) }}" class="text-decoration-none text-dark flex-grow-1">
                <img src="{{ asset('courses/' . $courseItem->image) }}" class="img-fluid rounded my-2" alt="{{ $courseItem->title }}">
                <h5 class="fw-bold">{{ $courseItem->long_title }}</h5>
                <p>{{ $courseItem->description }}</p>
            </a>
            <span class="fw-bold">{{ $courseItem->rate }} ⭐</span>
        </div>
    </div>
@endforeach 