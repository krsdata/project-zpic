<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            div, span, applet, object, iframe,
            h1, h2, h3, h4, h5, h6, p, blockquote, pre,
            a, abbr, acronym, address, big, cite, code,
            del, dfn, em, img, ins, kbd, q, s, samp,
            small, strike, strong, sub, sup, tt, var,
            b, u, i, center,
            dl, dt, dd, ol, ul, li,
            fieldset, form, label, legend,
            table, caption, tbody, tfoot, thead,
            article, aside, canvas, details, embed,
            figure, figcaption, footer, header, hgroup,
            menu, nav, output, ruby, section, summary,
            time, mark, audio, video {
                margin: 0;
                padding: 0;
                border: 0;
                font-size: 100%;
                font: inherit;
                vertical-align: baseline;
            }
            /* HTML5 display-role reset for older browsers */
            article, aside, details, figcaption, figure,
            footer, header, hgroup, menu, nav, section {
                display: block;
            }
            body {
                line-height: 1;
            }
            ol, ul {
                list-style: none;
            }
            blockquote, q {
                quotes: none;
            }
            blockquote:before, blockquote:after,
            q:before, q:after {
                content: '';
                content: none;
            }
            table {
                border-collapse: collapse;
                border-spacing: 0;
            }

            .page-break {
                page-break-after: always;
            }
            td {
                padding: 5px;
            }
            body {
            }
            td p {
                color: red;
            }
            table td {
                border: 1px solid black;
            }

        </style>
    </head>
    <body>

        <table>
            <tr>
                <td style='font-family: Times-Roman;'>
                    <strong>A: (TO)</strong>
                    <p>
                        <strong>{{ $toLocation->name }}</strong><br />
                        {{  $toLocation->description }}
                    <p>
                </td>
                <td colspan='3'>
                    COE/COD Nº {{ $bulk_id }}<br />
                    (Certificado de Entrega/Certificate of Delivery)
                </td>
            </tr>
            <tr>
                <td>
                    <strong>DE: (FROM)</strong>
                    <p>
                        <strong>{{ $fromLocation->name }}</strong><br />
                        {{ $fromLocation->description }}
                    <p>
                </td>
                <td>
                    <strong>DIA:</strong><br />
                    {{ date('d') }}
                </td>
                <td>
                    <strong>MES:</strong><br />
                    {{ date('m') }}
                </td>
                <td>
                    <strong>AÑO</strong><br />
                    {{ date('Y') }}
                </td>
            </tr>
            <tr>
                <td colspan='4'>
                    <strong>OBSERVACIONES:</strong><br />
                    {{ isset($results[0]['observaciones'])?$results[0]['observaciones']:'' }}
                </td>
            </tr>
            <tr>
                <td>
                    Número de cajas<br/>(No. of Boxes)
                </td>
                <td>{{ isset($results[0]['no_of_boxes'])?$results[0]['no_of_boxes']:'' }}</td>
                <td>
                    Peso de Envío (Weight of Shipment) KG
                </td>
                <td>{{ isset($results[0]['weight_of_shipment'])?$results[0]['weight_of_shipment']:''}}</td>
            </tr>
        </table>
        <table>
            <tr>
                <td>
                    ID
                </td>
                <td>
                    Part Number
                </td>
                <td>
                    DESCRIPCIÓN (DESCRIPTION)
                </td>
                <td>
                    CANT (QTY)
                </td>
                <td>
                    S/N
                </td>
                <td>
                    OBSERVACIONES: (Comments)
                </td>
            </tr>
            @foreach($results as $result)
            <tr>
                <td>{{ $result['stock_id'] }}</td>
                <td>{{ $result['part_number'] }} </td>
                <td>{{ $result['inventory_name'] }} </td>
                <td>{{ $result['stock_quantity'] }} </td>
                <td>{{ $result['stock_serial'] }} </td>
                <td>{{ isset($result['comment'])?$result['comment']:'' }}</td>
            </tr>
            @endforeach
        </table>
        <p style='padding: 20px 0;'>
            Aprobé recibir el envío , sin contar el contenido de las cajas (I approved receiving the shipment without counting the content of boxes )
        </p>
        <table style='width: 100%;'>
            <tr>
                <td style='width: 50%;'>
                    Name: ______________
                </td>
                <td style='width: 50%;'>
                    Date: ______________
                </td>
            </tr>
        </table>
    </body>
</html>