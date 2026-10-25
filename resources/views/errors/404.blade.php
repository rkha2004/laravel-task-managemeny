@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto py-12 text-center space-y-6">
    <div class="w-20 h-20 mx-auto rounded-3xl bg-rose-50 text-rose-500 border border-rose-100 flex items-center justify-center shadow-xs">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>

    <div>
        <span class="text-xs font-mono font-bold uppercase tracking-wider text-rose-600 bg-rose-50 px-2.5 py-1 rounded-md border border-rose-200">
            Error 404
        </span>
        <h1 class="text-2xl font-black text-slate-900 mt-3 tracking-tight">Task Tidak Ditemukan</h1>
        <p class="text-sm text-slate-500 mt-2 leading-relaxed">
            Maaf, task yang Anda cari tidak tersedia, memiliki ID yang salah, atau mungkin telah dihapus dari sistem.
        </p>
    </div>

    <div class="pt-2">
        <a href="{{ route('tasks.index') }}" 
           class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-xs transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar Task</span>
        </a>
    </div>
</div>
@endsection
