@extends('main')
@section('title', 'User - My Website')
@section('breadcrumb', 'User')
@section('page-title', 'User')

@section('content')
<div class="container">
    <div class="d-flex justify-content-end align-items-center mb-4">
        <div>
            <a class="btn btn-primary" href="{{ route('users.create') }}">Tambah User</a>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col" class="text-center">No</th>
                <th scope="col" class="text-center">Email</th>
                <th scope="col" class="text-center">Nama</th>
                <th scope="col" class="text-center">Role</th>
                <th scope="col" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php
            $id = 1;
            @endphp
            @foreach ($users as $user)
            <tr>
                <th scope="row" class="text-center">{{ $id++ }}</th>
                <td class="text-center">{{ $user->email }}</td>
                <td class="text-center">{{ $user->name}}</td>
                <td class="text-center">{{ $user->role}}</td>
                <td class="text-center">
                    <div class="d-grid gap-4 d-md-flex justify-content-md-end">
                        <a href="{{ route('users.edit', $user['id']) }}"><button type="button"
                                class="btn btn-warning">Edit</button></a>
                        <form action="{{ route('users.destroy', $user['id']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
