@extends('adminlte::page')

@section('title', 'Relatórios | Dominun')

@section('content_header')
    <div class="row d-flex col-12 justify-content-between">
        <h1 class="m-0 text-dark"><i class="fas fa-chart-barfas fa-chart-bar"></i> Relatórios</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active"><a>Relatórios</a></li>
            </ol>
        </nav>
    </div>
@stop

@section('css')
    <style>
        .dt-button {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
            box-shadow: none;
            display: inline-block;
            font-weight: 400;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            border: 1px solid transparent;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: .25rem;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .menu-report {
            text-align: center;
            padding-bottom: 2%;
        }

        .table-style {
            padding-top: 1%;
            padding-bottom: 1%;
        }
    </style>
@stop

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-outline">
                        <div class="card-body">
                            <h3 class="menu-report"> Relatórios de Transferências Sintético</h3>
                            <form action="">
                                <div class="form-group d-flex col-12 col-lg-6">
                                    <input class="form-control border border-success" name="filter_transactions"
                                        id="filter_transactions">
                                    <button class="btn btn-success ml-2" type="submit">Filtrar</button>
                                </div>
                            </form>
                            <div class="table-responsive pr-4 table-style">
                                <table class="table table-striped table-bordered datatable nowrap" id="b1">
                                    <thead>
                                        <!-- <th></th> -->
                                        <th>Criação</th>
                                        <th>Status</th>
                                        <th>Tipo</th>
                                        <th>Total</th>
                                    </thead>
                                    {{-- <tbody>
                                        @foreach ($sortTransactions as $pay)
                                            @php
                                                $Grandtotal += $pay->$amount;
                                            @endphp
                                            <tr>
                                                <!-- <td></td> -->
                                                <td>{{ getDateTimeConvertPagarme($pay->date_created) }}</td>
                                                <td>{{ convertStatus($pay->status) }}</td>
                                                <td>{{ convertType($pay->type) }}</td>
                                                <td>R$ {{ getValue($pay->amount) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total no período: {{ $Grandtotal }}</th>
                                            </tr>
                                        @endforeach
                                    </tbody> --}}
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('js')
    <script>
        const dateMovement = "{{ request()->query('movement_date') }}"
        const dueDate = "{{ request()->query('due_date') }}"
        const paymentMethod = "{{ request()->query('payment_method_id') }}"
        const initialValue = "{{ request()->query('amount_start') }}"
        const finalValue = "{{ request()->query('amount_finish') }}"

        $('#b1').DataTable({
            language: {
                processing: "Tratamento em andamento...",
                search: "Busca&nbsp;:",
                lengthMenu: "Mostrar _MENU_ entradas",
                info: "Mostrando _START_ de _END_ de _TOTAL_ entradas.",
                infoEmpty: "informações vazias",
                infoFiltered: "informações filtradas",
                loadingRecords: "Carregamento em andamento...",
                zeroRecords: "Zero registros",
                emptyTable: "Tabela vazia",
                paginate: {
                    first: "Primeiro",
                    previous: "Anterior",
                    next: "Próximo",
                    last: "Última"
                },
                aria: {
                    sortAscending: ": Classificar Ascendente",
                    sortDescending: ": Classificar Descendente"
                }
            },
            paginate: false,
            filter: false,
            info: false,
            dom: 'Bfrtip',
            buttons: [{
                extend: 'copy',
                text: 'Copiar',
                action: function(e, dt, node, config) {
                    copyClipboard(dt.body()[0].innerText);
                    Swal.fire({
                        title: 'Sucesso!',
                        type: 'success',
                        text: 'Tabela copiada com sucesso!',
                        position: 'top-end',
                        toast: true,
                        showConfirmButton: false
                    })
                }
            }, 'csv', 'excel', 'pdf', 'print']
        });



        /** Funções do filter */
        /** Input de transação */
        const filterTransactions = "{{ request()->query('filter_transactions') }}"
        $('#filter_transactions').val(filterTransactions);
        $('#filter_transactions').trigger('change');

        $('#filter_transactions').daterangepicker({
            ranges: {
                'Hoje': [moment(), moment()],
                'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
                'Este Mês': [moment().startOf('month'), moment().endOf('month')],
                'Último Mês': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                    'month')]
            },
            autoUpdateInput: false,
            "locale": {
                "format": "DD/MM/YYYY",
                "separator": " - ",
                "applyLabel": "Aplicar",
                "cancelLabel": "Cancelar",
                "fromLabel": "De",
                "toLabel": "Para",
                "customRangeLabel": "Personalizado",
                "weekLabel": "M",
                "daysOfWeek": [
                    "Dom",
                    "Seg",
                    "Ter",
                    "Qua",
                    "Qui",
                    "Sex",
                    "Sáb"
                ],
                "monthNames": [
                    "Janeiro",
                    "Fevereiro",
                    "Março",
                    "Abril",
                    "Maio",
                    "Junho",
                    "Julho",
                    "Agosto",
                    "Setembro",
                    "Outubro",
                    "Novembro",
                    "Dezembro"
                ],
                "firstDay": 1
            },
            "startDate": "{{ $start1->format('d/m/Y') }}",
            "endDate": "{{ $end1->format('d/m/Y') }}"
        });

        $('#filter_transactions').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'))
                .change();
        });

        $('#filter_transactions').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    </script>
@endsection
