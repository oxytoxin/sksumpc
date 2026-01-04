<div x-data='{
                init(){
                    Livewire.hook(`commit`, ({ succeed }) => {
                        succeed(() => {
                            setTimeout(() => {
                                const firstErrorMessage = document.querySelector(`[data-validation-error]`)

                                if (firstErrorMessage !== null) {
                                    firstErrorMessage.scrollIntoView({ block: `center`, inline: `center` })
                                }
                            }, 0)
                        })
                    })
                    }
                }'>
</div>
@php
    $customcss = Vite::asset('resources/css/filament/app/theme.css');
@endphp
<script type='text/javascript'>
    function printOut(data, title) {
        const new_window = window.open('', title, 'height=1000,width=1000');
        new_window.document.write('<html lang="en-US"><head>');
        new_window.document.write('<title>' + title + '</title>');
        new_window.document.write(`<link rel='stylesheet' href="{{ $customcss }}"><body>`);
        new_window.document.write(data);
        new_window.document.close();
        new_window.focus();
        setTimeout(() => {
            new_window.print();
        }, 1000);
        return false;
    }
</script>