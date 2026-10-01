<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; max-width: 720px; margin: 40px auto; padding: 0 16px; }
        img { max-width: 100%; border-radius: 8px; }
        .price { font-size: 28px; color: #2e7d32; font-weight: bold; margin: 16px 0; }
        form { display: grid; gap: 12px; max-width: 360px; margin-top: 24px; }
        input { padding: 10px; font-size: 16px; }
        button { padding: 12px; font-size: 16px; background: #2e7d32; color: #fff; border: none; border-radius: 6px; cursor: pointer; }
        button:hover { background: #256428; }
        .error { color: #c62828; font-size: 14px; }
    </style>
</head>
<body>
    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
    <h1>{{ $product->name }}</h1>
    <p>{{ $product->description }}</p>
    <p class="price">R$ {{ number_format($product->price, 2, ',', '.') }}</p>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="text" name="name" placeholder="Nome completo" value="{{ old('name') }}" required>
        <input type="email" name="email" placeholder="E-mail" value="{{ old('email') }}" required>
        <input type="text" name="cpf_cnpj" placeholder="CPF" value="{{ old('cpf_cnpj') }}" required>
        <button type="submit">Comprar agora</button>
    </form>
</body>
</html>