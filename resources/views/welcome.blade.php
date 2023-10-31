<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Id Prev</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet"/>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">


    <!-- Styles -->
    <style>
        h1,
        h3 {
            font-family: 'Bebas Neue', cursive !important;
        }

        .progress{
            height: 3px;
            background-color: #FCFCFC;
            position: relative;
        }

        .progress .progress-bar{
        position: absolute;
        height: 100%;
        background-color: #E1E1E1;
        animation: progress-animation 9s infinite;
        }

        @keyframes progress-animation{
            0% { width: 0%; }
            
            100% { width: 100%}
            
        }

    </style>
</head>

<body>
    <header class="" style="background-image: url('/images/banner-principal-2.jpg'); background-size: cover;">
        <div class="p-3 md:container md:m-auto">

            <div class="flex w-full justify-center md:justify-between">
                <div class="h-16 pt-3">
                    <a href=""><img src="{{ asset('images/logotipo.png') }}" alt="Logotipo Id Preve"
                            style="width: 250px;" /></a>
                </div>
                <div class="flex h-16 items-center justify-center mt-5 hidden md:block">
                    <a href="/login" class="text-black py-2 px-4 bg-blue-900 rounded-md text-white shadow">Área do
                        Franqueado</a>
                </div>
            </div>

            <div class="flex my-10 md:my-40">
                <div class="w-full text-center mb-10 md:mb-0 md:flex-1">
                    <h1 class="text-white text-5xl leading-none" style="text-shadow: 1px 1px 3px #696969;">Somos a maior
                        rede previdenciária do Brasil.<br />
                        <span class="text-blue-800">Mais de 10 anos de mercado e 14 mil clientes satisfeitos.</span>
                    </h1>
                    <br />
                    <a class="bg-blue-500 py-4 block m-2 rounded shadow text-white text-bold md:hidden"
                        href="https://wa.me/5511914080948" target="blank"
                        class="text-white px-5 py-3 shadow-sm mt-4 m-auto text-center rounded-md bg-green-700 md:ml-5 md:w-48">Resolva
                        seu problema com o INSS aqui.</a>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row">
            <div class="flex justify-center text-white bg-blue-800 opacity-75 md:flex-1">
                <div class="py-12">
                    <ul class="text-xl">
                        <li>Aposentadorias</li>
                        <li>Pensões</li>
                        <li>Loas</li>
                        <li>Revisões</li>
                        <li>Auxílios</li>
                        <li>Fraudes com empréstimo consignado</li>
                    </ul>
                </div>
            </div>
            <div class="flex justify-center text-white bg-blue-900 opacity-75 md:flex-1">
                <div class="py-12">
                    <h2 class="text-2xl mb-2">O INSS negou seu pedido?<br />Dúvidas ou problemas com o INSS?</h2>
                    <p class="text-xl">Resolvemos seu problema em todo o Brasil.</p>
                    <a href="https://wa.me/5511914080948" target="blank"
                        class="text-white mt-5 px-5 py-3 shadow-sm mt-4 m-auto text-center rounded-md bg-green-600 block">
                        Resolva seu problema com o INSS aqui.</a>
                    <div class="flex justify-center mt-5 md:justify-start">
                        <a href="https://instagram.com/idprevofc?igshid=YmMyMTA2M2Y=" target="blank">
                            <img src="{{ asset('images/icons/instagram.png') }}" alt="Instagram" class="w-8" />
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </header>

    <!-- sessão de franqueados -->
    <section class="bg-gray-100">
        <div class="flex flex-col px-5 py-10 md:flex-row md:py-20 md:justify-between md:container md:m-auto">

            <div class="flex flex-col mb-10 md:mb-0 md:flex-1">

                <img src="{{ asset('images/logotipo.png') }}" alt="Logotipo" class="w-56 mb-5 m-auto md:m-0 md:mb-10" />
                <h1 class="text-5xl leading-tight mb-5 text-center md:text-left md:border-l-8 md:border-blue-600 md:pl-5"
                    style="text-shadow: 1px 1px 3px #696969;">
                    Seja nosso franqueado e <span class="text-blue-700">receba clientes</span> todos os dias.
                </h1>
                <p class="text-gray-500 mb-6 text-xl text-center md:text-left md:ml-6">A melhor franquia para qualquer
                    pessoa que queira ganhar dinheiro com a maior área do Direito no Brasil.</p>
                <ul class="ml-12 mb-5  list-disc">
                    <li>Primeira e única rede previdenciária do Brasil.</li>
                    <li>Desde 2012 com mais de 14 mil clientes.</li>
                    <li class="font-bold">Baixo investimento e alta rentabilidade.</li>
                    <li>Plataforma própria com gestão simplificada.</li>
                    <li>Clientes, advogados especializados, despachantes, peritos, marketing digital, assessoria
                        especializada, tudo incluso e sem custo adicional.</li>
                </ul>
                <p class="mb-5 text-center md:text-left md:ml-6">A melhor franquia para advogados, contadores, bacharéis
                    ou qualquer pessoa que queira ganhar dinheiro com o INSS.</p>
                <p class="mb-5 text-center md:text-left md:ml-6">Sem necessidade de ter OAB ou experiência na área
                    previdenciária, prestamos total suporte.</p>
                <a href="https://wa.me/5511914080948" target="blank"
                    class="w-full py-3 bg-green-600 text-white font-bold shadow-sm rounded-md text-center md:ml-5">QUERO
                    SER UM FRANQUEADO DE SUCESSO.</a>

            </div>

            <div class="hidden md:flex md:flex-1 md:justify-end">
                <div class="grid grid-rows-4 grid-flow-col gap-4">
                    <div class="row-span-4 col-span-1 w-64"
                        style="background-image: url('/images/image-1.jpg'); background-size: cover;"></div>
                    <div class="row-span-2 col-span-1 w-64"
                        style="background-image: url('/images/image-2.jpg'); background-size: cover;"></div>
                    <div
                        class="row-span-2 col-span-1 bg-blue-500 w-64 bg-blue-800 shadow-sm rounded-md flex justify-center items-center p-10">
                        <p class="text-2xl text-white text-center">Sua única preocupação é fechar contratos e receber
                            honorários.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- end -->

    <section class="pb-12 md:container md:m-auto">
        <video class="m-auto" width="720" height="576" controls autoplay>
            <source src="{{ asset('movie/institucional.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </section>

    <!-- testemunhais -->
    <section class="pb-12 md:container md:m-auto">

        <h1 class="text-5xl text-center" style="text-shadow: 1px 1px 3px #696969;">
            Veja o que dizem <span class="text-blue-700">nossos clientes</span>.
        </h1>

        @foreach ($testimonials as $value)
            <div class="mySlides border rounded-md m-2">
                <div class="flex flex-col items-center m-4 md:flex-row">
    
                    @if (isset($value->image))
                        <img src="{{ asset('storage/' . $value->image) }}" alt="Photo"
                            class="bg-gray-500 rounded-full w-48 h-48 mr-8 border" />
                    @else
                        <img src="https://dummyimage.com/28x28/b6b7ba/fff" alt="Photo"
                            class="bg-gray-500 rounded-full w-48 h-48 mr-8 border" />
                    @endif
                    <div class="flex flex-col items-center m-4 md:items-start md:m-0">
                        <strong class="text-2xl">{{ $value->name }}</strong>
                        <p>{{ $value->description }}</p>
                    </div>
    
                </div>
                <div class="progress">
                    <div class="progress-bar"></div>
                </div>
            </div>
        @endforeach

    </section>
    <!-- end -->

    <script>
        let slideIndex = 0;
        showSlides();
        
        function showSlides() {
          let i;
          let slides = document.getElementsByClassName("mySlides");
          for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";  
          }
          slideIndex++;
          if (slideIndex > slides.length) {slideIndex = 1}    

          slides[slideIndex-1].style.display = "block";
          setTimeout(showSlides, 9000); // Change image every 2 seconds
        }
        </script>

    <footer class="bg-blue-900 w-full p-5 md:py-10">

        <div class="md:container md:m-auto">

            <div class="grid grid-cols-1 gap-2 md:grid-cols-4 md:gap-4">
                <div class="flex justify-center md:justify-start">
                    <a href="https://instagram.com/idprevofc?igshid=YmMyMTA2M2Y=" target="blank" class="m-3">
                        <img src="{{ asset('images/icons/instagram.png') }}" alt="Instagram" class="w-10 h-10" />
                    </a>
                </div>
                <div class="">
                    <h3 class="text-white text-2xl mb-3 md:mb-5 border-l-8 border-blue-800 pl-5"
                        style="text-shadow: 1px 1px 3px #000;">Contato</h3>
                    <p class="flex text-gray-400 mb-3">
                        <img src="{{ asset('images/icons/address.png') }}" alt="endereço" class="w-5 h-5 mr-3" />
                        Matriz - Maceió/AL
                    </p>
                    <p class="flex text-gray-400 mb-3">
                        <img src="{{ asset('images/icons/address.png') }}" alt="endereço" class="w-5 h-5 mr-3" />
                        Digital - São Paulo/SP
                    </p>
                    <p class="flex text-gray-400 mb-3 md:mb-5">
                        <img src="{{ asset('images/icons/e-mail.png') }}" alt="email" class="w-5 h-5 mr-3" />
                        falecom@idprev.com.br
                    </p>
                    <p class="flex text-gray-400 mb-3 md:mb-5">
                        <img src="{{ asset('images/icons/phone.png') }}" alt="whatsapp" class="w-5 h-5 mr-3" /> +55
                        82 3435-9092
                    </p>
                    <p class="flex text-gray-400 mb-3 md:mb-5">
                        <img src="{{ asset('images/icons/whatsapp.png') }}" alt="whatsapp" class="w-5 h-5 mr-3" />
                        +55 11 91408-0948
                    </p>
                </div>
                <div class="">
                    <h3 class="text-white text-2xl mb-3 md:mb-5 border-l-8 border-blue-800 pl-5"
                        style="text-shadow: 1px 1px 3px #000;">Suporte</h3>
                    <a href="/login" class="text-gray-400 block hover:text-white">- Área do franqueado.</a>
                    <a href="https://wa.me/5511914080948" target="blank"
                        class="text-gray-400 block hover:text-white">- Seja um franqueado de sucesso.</a>
                    <a href="https://wa.me/5511914080948" target="blank"
                        class="text-gray-400 block hover:text-white">- Resolva seu problema com o INSS aqui.</a>
                </div>
                <div class="">
                    <h3 class="text-white text-2xl mb-3 md:mb-5 border-l-8 border-blue-800 pl-5"
                        style="text-shadow: 1px 1px 3px #000;">Full Stack Developer</h3>
                    <a href="https://wa.me/5582988365125" target="blank"
                        class="text-gray-400 block hover:text-white">Denis Buarque</a>
                </div>
            </div>

        </div>

    </footer>
    <!-- end rodape -->
    

</body>

</html>
