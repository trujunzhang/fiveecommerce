/**
 * Created by tagdiv on 12.08.2015.
 */

/* global jQuery:{} */
/* global dymo:{} */
/* global QRErrorCorrectLevel:{} */
/* global QRCode:{} */


(function($) {
    'use strict';


    jQuery('.td-shipping-label').click(function(event) {
        event.preventDefault();

        /*
         This variable object will point to the global tdWooLabel, if it exists.
         It must be this form
             {
                 tdWooLabelDefaultInfo      : - string(HTML)- the default formatted html of the browser printing
                 tdWooLabelDYMOInfo         : - string      - the DYMO format (text with line separators)

                 tdWooLabelPrintingFormat   : - string      - printing format
                 tdWooLabelSellerLogo       : - string      - the base64 data of the sellor logo image
                 tdWooLabelCustomURL        : - string(URL) - the custom URL
                 tdWooLabelShippingImage    : - string      - the type of shipping image chosen to be shown from the plugin panel
                 tdWooLabelShippingImageURL : - string(URL) - the URL for QR images that will be generated

                 tdWooLabelTemplateURL      : - string(URL) - the plugin url for using the label template (just for the default browser printing)
             }
          */

        var localTdWooLabel;

        if ('undefined' === typeof window.tdWooLabel) {
            return;
        } else {
            localTdWooLabel = window.tdWooLabel;
        }

        /*
            The image that should be shown on the label: no image, the seller logo image or the qr code (order url, map url or custom url)
            The fomat will be:
             {
                imageObj: - the HTMLImageElement
                imageBase64Data: - the base64 image data
             }
        */
        var shippingImage;

        if (('undefined' !== typeof localTdWooLabel.tdWooLabelShippingImage) && ('' !== localTdWooLabel.tdWooLabelShippingImage)) {

            switch (localTdWooLabel.tdWooLabelShippingImage) {

                // The seller logo is base64 formatted on the server side
                case 'seller_logo':
                    if (('undefined' !== typeof localTdWooLabel.tdWooLabelSellerLogo) && ('' !== localTdWooLabel.tdWooLabelSellerLogo)) {

                        var image = new Image();
                        image.src = 'data:image/png;base64,' + localTdWooLabel.tdWooLabelSellerLogo;

                        shippingImage = {
                            imageObj: image,
                            imageBase64Data: localTdWooLabel.tdWooLabelSellerLogo
                        };
                    }
                    break;

                // The order_url and map_url are Woo generated URLs
                case 'order_url':
                case 'map_url':
                    if (('undefined' !== typeof localTdWooLabel.tdWooLabelShippingImageURL) && ('' !== localTdWooLabel.tdWooLabelShippingImageURL)) {
                        shippingImage = td_get_qr_code_image(localTdWooLabel.tdWooLabelShippingImageURL);
                    }
                    break;

                // The custom URL is defined in plugin panel.
                case 'custom_url':
                    if (('undefined' !== typeof localTdWooLabel.tdWooLabelCustomURL) && ('' !== localTdWooLabel.tdWooLabelCustomURL)) {
                        shippingImage = td_get_qr_code_image(localTdWooLabel.tdWooLabelCustomURL);
                    }
                    break;
            }
        }


        // The browser default printing.
        if (('undefined' === typeof localTdWooLabel.tdWooLabelPrintingFormat) || ('' === localTdWooLabel.tdWooLabelPrintingFormat)) {

            (function(tdWindow){

                /**
                 * Put the info label and the image in the label template.
                 *
                 * @param jqDocument The document of the opened window.
                 */
                function fill_label_template(jqDocument) {

                    var labelInfo = jqDocument.find('#label_info');

                    if (labelInfo.length && ('undefined' !== typeof localTdWooLabel.tdWooLabelDefaultInfo)) {
                        labelInfo.html(localTdWooLabel.tdWooLabelDefaultInfo);
                    }

                    var labelImage = jqDocument.find('#label_image');
                    if (labelImage.length && ('undefined' !== typeof shippingImage)) {
                        shippingImage.imageObj.width = 200;
                        labelImage.html(shippingImage.imageObj.outerHTML);
                    }
                }

                /*
                    Because using window.open does not ensure the real document is loaded, we use an interval for
                    checking content of the 'body' element. It happens at loading to get content of a blank page <html><head></head><body></body></html>
                 */
                var htmlBodies = $(tdWindow.document).find('body');

                if (htmlBodies.length > 0 && '' === htmlBodies[0].innerHTML) {
                    var checkInterval = setInterval(function() {

                        if ('' !== $(tdWindow.document).find('body')[0].innerHTML) {
                            clearInterval(checkInterval);

                            fill_label_template($(tdWindow.document));
                        }
                    }, 100);

                    // To stop the checkInterval after 2000 ms (a reliable loading time)
                    setTimeout(function() {
                        if ('undefined' === typeof checkInterval) {
                            clearInterval(checkInterval);
                        }
                    }, 2000);

                } else {
                    fill_label_template($(tdWindow.document));
                }

                tdWindow.document.close(); // necessary for IE >= 10
                tdWindow.focus(); // necessary for IE >= 10

                //td_window.print();
                //td_window.close();

            })(window.open(localTdWooLabel.tdWooLabelTemplateURL + '/templates/template.php' , '', 'height=600, width=800'));


        } else {

            // The DYMO printing
            try {
                var td_custom_content = '';

                if ('undefined' !== typeof shippingImage) {

                    td_custom_content +=
                        '<ObjectInfo>' +
                            '<ImageObject>' +
                                '<Name>QRCode</Name>' +
                                '<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />' +
                                '<BackColor Alpha="0" Red="255" Green="255" Blue="255" />' +
                                '<LinkedObjectName></LinkedObjectName>' +
                                '<Rotation>Rotation0</Rotation>' +
                                '<IsMirrored>False</IsMirrored>' +
                                '<IsVariable>False</IsVariable>' +
                                '<Image>' + shippingImage.imageBase64Data + '</Image>' +
                                '<ScaleMode>Uniform</ScaleMode>' +
                                '<BorderWidth>0</BorderWidth>' +
                                '<BorderColor Alpha="255" Red="0" Green="0" Blue="0" />' +
                                '<HorizontalAlignment>Center</HorizontalAlignment>' +
                                '<VerticalAlignment>Center</VerticalAlignment>' +
                            '</ImageObject>' +
                            '<Bounds X="3280" Y="275" Width="1460" Height="1460" />' +
                        '</ObjectInfo>';
                }

                // open label
                var labelXml =
                    '<?xml version="1.0" encoding="utf-8"?>' +
                    '<DieCutLabel Version="8.0" Units="twips">' +
                        '<PaperOrientation>Landscape</PaperOrientation>' +
                        '<Id>LargeAddress</Id>' +
                        '<PaperName>30321 Large Address</PaperName>' +
                        '<DrawCommands>' +
                            '<RoundRectangle X="0" Y="0" Width="2025" Height="5020" Rx="270" Ry="270" />' +
                        '</DrawCommands>' +
                        '<ObjectInfo>' +
                            '<TextObject>' +
                                '<Name>ShippingInfo</Name>' +
                                '<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />' +
                                '<BackColor Alpha="0" Red="255" Green="255" Blue="255" />' +
                                '<LinkedObjectName></LinkedObjectName>' +
                                '<Rotation>Rotation0</Rotation>' +
                                '<IsMirrored>False</IsMirrored>' +
                                '<IsVariable>True</IsVariable>' +
                                '<HorizontalAlignment>Left</HorizontalAlignment>' +
                                '<VerticalAlignment>Middle</VerticalAlignment>' +
                                '<TextFitMode>ShrinkToFit</TextFitMode>' +
                                '<UseFullFontHeight>True</UseFullFontHeight>' +
                                '<Verticalized>False</Verticalized>' +
                                '<StyledText/>' +
                            '</TextObject>' +
                            '<Bounds X="322" Y="70" Width="2600" Height="1870" />' +
                        '</ObjectInfo>' +

                        td_custom_content +

                    '</DieCutLabel>';

                var printerLabel = dymo.label.framework.openLabelXml(labelXml);

                // set label text
                if ('undefined' !== typeof localTdWooLabel.tdWooLabelDYMOInfo) {
                    printerLabel.setObjectText('ShippingInfo', localTdWooLabel.tdWooLabelDYMOInfo);
                }

                // select printer to print on
                // for simplicity sake just use the first LabelWriter printer
                var printers = dymo.label.framework.getPrinters();
                if (0 === printers.length) {
                    throw "No DYMO printers are installed. Install DYMO printers.";
                }

                var printerName = "";
                for (var i = 0; i < printers.length; ++i) {
                    if ("LabelWriterPrinter" === printers[i].printerType) {
                        printerName = printers[i].name;
                        break;
                    }
                }

                if ("" === printerName) {
                    throw "No LabelWriter printers found. Install LabelWriter printer";
                }

                // finally print the label
                printerLabel.print(printerName);
            }
            catch (e) {
                window.alert(e.message || e);
            }
        }
    });


    /**
     * The QRCode generator
     *
     * @param url String url being codded into the QR
     * @returns {{imageObj: Image, imageBase64Data: string}}
     */
    function td_get_qr_code_image(url) {

        var options = {
            text            : url,
            size		    : 1460,
            background      : "#ffffff",
            foreground      : "#000000",
            render		    : "canvas",
            typeNumber	    : -1,
            correctLevel	: QRErrorCorrectLevel.Q
        };

        var qrcode = new QRCode(options.typeNumber, options.correctLevel);
        qrcode.addData(options.text);
        qrcode.make();

        var qrModuleCount = qrcode.getModuleCount();

        // create canvas element
        var canvas = document.createElement('canvas');
        canvas.width = options.size;
        canvas.height = options.size;
        var ctx = canvas.getContext('2d');

        // compute tileSize based on options.size
        var tileSize = options.size / qrModuleCount;

        // draw in the canvas
        for( var row = 0; row < qrModuleCount; row++ ){
            for( var col = 0; col < qrModuleCount; col++ ){
                var size = (Math.ceil((col + 1) * tileSize) - Math.floor(col * tileSize));
                ctx.fillStyle = qrcode.isDark(row, col) ? options.foreground : options.background;
                ctx.fillRect(Math.round(col * tileSize), Math.round(row * tileSize), size, size);
            }
        }

        var dataImageURL = canvas.toDataURL('image/png', 1.0);

        var image = new Image();
        image.src = dataImageURL;

        return {
            imageObj: image,
            imageBase64Data: dataImageURL.replace(/^data:image\/(png|jpg);base64,/, '')
        };

    }

})(jQuery);



