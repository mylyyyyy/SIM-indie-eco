@extends('layouts.admin')

@section('content')
{{-- Load Animate.css --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    /* 3D Perspective */
    .perspective-1000 { perspective: 1000px; }

    /* 3D Card Effect */
    .card-3d {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        transform-style: preserve-3d;
        backface-visibility: hidden;
    }
    
    /* Gradient Text */
    .text-gradient {
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-image: linear-gradient(to right, #1e293b, #2563eb);
    }

    /* Content Typography (Article Style) */
    .article-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #334155;
    }
    .article-content h1 { font-size: 2rem; font-weight: 800; margin: 1.5em 0 0.5em; color: #0f172a; }
    .article-content h2 { font-size: 1.5rem; font-weight: 700; margin: 1.5em 0 0.5em; color: #1e293b; }
    .article-content p { margin-bottom: 1.2em; }
    .article-content ul, .article-content ol { margin-left: 1.5em; margin-bottom: 1.2em; }
    .article-content ul { list-style-type: disc; }
    .article-content ol { list-style-type: decimal; }
    .article-content blockquote {
        border-left: 4px solid #3b82f6;
        padding: 1rem 1.5rem;
        font-style: italic;
        background: #f8fafc;
        border-radius: 0 1rem 1rem 0;
        margin: 1.5em 0;
        color: #475569;
    }
    .article-content img {
        border-radius: 1rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        margin: 2em auto;
        max-width: 100%;
    }
    .article-content a { color: #2563eb; text-decoration: underline; text-underline-offset: 4px; }
</style>

<div class="max-w-5xl mx-auto space-y-8 pb-12 perspective-1000">

    {{-- HEADER --}}
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 animate__animated animate__fadeInDown">
        <div>
            <h1 class="text-3xl font-extrabold text-gradient">Detail Berita</h1>
            <p class="text-slate-500 mt-1 font-medium">Pratinjau tampilan artikel berita.</p>
        </div>
        <a href="{{ route('news.index') }}" class="group inline-flex items-center gap-2 bg-white text-slate-600 font-bold py-2.5 px-5 rounded-xl shadow-sm border border-slate-200 hover:border-slate-300 hover:bg-slate-50 transition-all active:scale-95">
            <i class="fas fa-arrow-left text-slate-400 group-hover:text-slate-600 transition-colors"></i>
            <span>Kembali</span>
        </a>
    </header>

    {{-- MAIN CONTENT CARD --}}
    <div class="card-3d bg-white rounded-[2rem] shadow-2xl border border-slate-100 overflow-hidden animate__animated animate__fadeInUp delay-100">
        
        {{-- HERO IMAGE SECTION --}}
        <div class="relative w-full h-[400px] bg-slate-200 group">
            @if(is_array($news->image) && count($news->image) > 0)
                <img src="{{ $news->image[0] }}" alt="{{ $news->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                {{-- Overlay Gradient --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-300">
                    <i class="fas fa-image text-6xl text-slate-400"></i>
                </div>
            @endif

            {{-- Floating Meta Info --}}
            <div class="absolute top-6 right-6 flex gap-2">
                @if($news->status == 'published')
                    <span class="px-4 py-2 rounded-full bg-emerald-500/90 backdrop-blur-md text-white text-xs font-bold shadow-lg flex items-center gap-2">
                        <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span> DITERBITKAN
                    </span>
                @else
                    <span class="px-4 py-2 rounded-full bg-amber-500/90 backdrop-blur-md text-white text-xs font-bold shadow-lg flex items-center gap-2">
                        <i class="fas fa-pen"></i> DRAFT
                    </span>
                @endif
            </div>

            {{-- Title Over Image --}}
            <div class="absolute bottom-0 left-0 w-full p-8 md:p-10 text-white">
                <div class="flex items-center gap-4 text-sm font-medium text-slate-300 mb-2">
                    <span class="flex items-center gap-1"><i class="far fa-calendar-alt"></i> {{ $news->created_at->translatedFormat('d F Y') }}</span>
                    <span>&bull;</span>
                    <span class="flex items-center gap-1"><i class="far fa-clock"></i> {{ $news->created_at->format('H:i') }} WIB</span>
                </div>
                <h1 class="text-3xl md:text-5xl font-black leading-tight shadow-black drop-shadow-lg">
                    {{ $news->title }}
                </h1>
            </div>
        </div>

        {{-- ARTICLE BODY --}}
        <div class="p-8 md:p-12">
            <div class="article-content">
                {!! $news->content !!}
            </div>
        </div>

        {{-- FOOTER ACTIONS --}}
        <div class="bg-slate-50 p-6 md:p-8 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-sm text-slate-500 italic">
                ID Berita: #{{ $news->id }} &bull; Terakhir diupdate: {{ $news->updated_at->diffForHumans() }}
            </div>

            <div class="flex gap-3">
                <a href="{{ route('news.edit', $news->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white border-2 border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-blue-50 hover:border-blue-200 hover:text-blue-600 transition-all shadow-sm active:scale-95">
                    <i class="fas fa-edit"></i> Edit Berita
                </a>
                
                <button onclick="confirmDelete(event)" class="inline-flex items-center gap-2 px-6 py-3 bg-red-100 text-red-600 font-bold rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm hover:shadow-red-200 active:scale-95">
                    <i class="fas fa-trash-alt"></i> Hapus
                </button>

                <form id="delete-form" action="{{ route('news.destroy', $news->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const swal3DConfig = {
        showClass: { popup: 'animate__animated animate__zoomInDown animate__faster' },
        hideClass: { popup: 'animate__animated animate__zoomOutUp animate__faster' },
        customClass: {
            popup: 'rounded-3xl shadow-2xl border-4 border-white/50 backdrop-blur-xl',
            title: 'text-2xl font-black text-slate-800',
            confirmButton: 'rounded-xl px-6 py-3 font-bold shadow-lg bg-red-600 text-white mr-2',
            cancelButton: 'rounded-xl px-6 py-3 font-bold shadow-lg bg-slate-200 text-slate-600'
        },
        buttonsStyling: false
    };

    function confirmDelete(event) {
        event.preventDefault();
        
        Swal.fire({
            ...swal3DConfig,
            title: 'Hapus Berita?',
            html: "Artikel ini akan dihapus permanen.<br>Anda tidak bisa mengembalikannya.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form').submit();
            }
        });
    }
</script>
@endsection