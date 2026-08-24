@extends('layouts.admin')
@section('title', 'Edit Uji Laboratorium - Admin Pangan Cerdas (Panca) Maryadat Kota Tual')
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-eyedropper me-2"></i>Edit Uji Laboratorium</h5>
<div class="card border-0 shadow-sm rounded-4 p-4" style="max-width:1200px">
    @include('admin.food-safety.lab-test._form', ['labTest' => $labTest])
</div>
@endsection
