{!! '<'.'?xml version="1.0" encoding="utf-8" standalone="no"?'.'>' !!}
<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
         xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
         xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
         xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
         xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
    <ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
    </ext:UBLExtensions>
    <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
    <cbc:CustomizationID>2.0</cbc:CustomizationID>
    <cbc:ID>{{ $document['series'] }}-{{ $document['number'] }}</cbc:ID>
    <cbc:IssueDate>{{ $document['date_of_issue'] }}</cbc:IssueDate>
    <cbc:IssueTime>{{ $document['time_of_issue'] ?? '00:00:00' }}</cbc:IssueTime>
    <cbc:InvoiceTypeCode listID="{{ $document['operation_type_id'] ?? '0101' }}">{{ $document['document_type_id'] }}</cbc:InvoiceTypeCode>
    <cbc:DocumentCurrencyCode>{{ $document['currency_type_id'] }}</cbc:DocumentCurrencyCode>
    
    <cac:Signature>
        <cbc:ID>IDSignia</cbc:ID>
        <cac:SignatoryParty>
            <cac:PartyIdentification>
                <cbc:ID>{{ $company['ruc'] }}</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyName>
                <cbc:Name><![CDATA[{{ $company['trade_name'] ?? $company['name'] }}]]></cbc:Name>
            </cac:PartyName>
        </cac:SignatoryParty>
        <cac:DigitalSignatureAttachment>
            <cac:ExternalReference>
                <cbc:URI>#IDSignia</cbc:URI>
            </cac:ExternalReference>
        </cac:DigitalSignatureAttachment>
    </cac:Signature>

    <cac:AccountingSupplierParty>
        <cac:Party>
            <cac:PartyIdentification>
                <cbc:ID schemeID="6">{{ $company['ruc'] }}</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyName>
                <cbc:Name><![CDATA[{{ $company['trade_name'] ?? $company['name'] }}]]></cbc:Name>
            </cac:PartyName>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA[{{ $company['name'] }}]]></cbc:RegistrationName>
                <cac:RegistrationAddress>
                    <cbc:ID>{{ $company['ubigeo'] ?? '150101' }}</cbc:ID>
                    <cbc:AddressTypeCode>0000</cbc:AddressTypeCode>
                    <cac:AddressLine>
                        <cbc:Line><![CDATA[{{ $company['address'] ?? '-' }}]]></cbc:Line>
                    </cac:AddressLine>
                    <cac:Country>
                        <cbc:IdentificationCode>PE</cbc:IdentificationCode>
                    </cac:Country>
                </cac:RegistrationAddress>
            </cac:PartyLegalEntity>
        </cac:Party>
    </cac:AccountingSupplierParty>

    <cac:AccountingCustomerParty>
        <cac:Party>
            <cac:PartyIdentification>
                <cbc:ID schemeID="{{ $customer['identity_document_type_id'] }}">{{ $customer['number'] }}</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA[{{ $customer['name'] }}]]></cbc:RegistrationName>
            </cac:PartyLegalEntity>
        </cac:Party>
    </cac:AccountingCustomerParty>

    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="{{ $document['currency_type_id'] }}">{{ $document['total_igv'] }}</cbc:TaxAmount>
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="{{ $document['currency_type_id'] }}">{{ $document['total_taxed'] }}</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="{{ $document['currency_type_id'] }}">{{ $document['total_igv'] }}</cbc:TaxAmount>
            <cac:TaxCategory>
                <cac:TaxScheme>
                    <cbc:ID>1000</cbc:ID>
                    <cbc:Name>IGV</cbc:Name>
                    <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
    </cac:TaxTotal>

    <cac:LegalMonetaryTotal>
        <cbc:LineExtensionAmount currencyID="{{ $document['currency_type_id'] }}">{{ $document['total_value'] }}</cbc:LineExtensionAmount>
        <cbc:TaxInclusiveAmount currencyID="{{ $document['currency_type_id'] }}">{{ $document['total'] }}</cbc:TaxInclusiveAmount>
        <cbc:PayableAmount currencyID="{{ $document['currency_type_id'] }}">{{ $document['total'] }}</cbc:PayableAmount>
    </cac:LegalMonetaryTotal>

    @foreach($items as $index => $row)
    <cac:InvoiceLine>
        <cbc:ID>{{ $index + 1 }}</cbc:ID>
        <cbc:InvoicedQuantity unitCode="{{ $row['unit_type_id'] ?? 'NIU' }}">{{ $row['quantity'] }}</cbc:InvoicedQuantity>
        <cbc:LineExtensionAmount currencyID="{{ $document['currency_type_id'] }}">{{ $row['total_value'] }}</cbc:LineExtensionAmount>
        <cac:PricingReference>
            <cac:AlternativeConditionPrice>
                <cbc:PriceAmount currencyID="{{ $document['currency_type_id'] }}">{{ $row['unit_price'] }}</cbc:PriceAmount>
                <cbc:PriceTypeCode>{{ $row['price_type_id'] ?? '01' }}</cbc:PriceTypeCode>
            </cac:AlternativeConditionPrice>
        </cac:PricingReference>
        <cac:TaxTotal>
            <cbc:TaxAmount currencyID="{{ $document['currency_type_id'] }}">{{ $row['total_igv'] }}</cbc:TaxAmount>
            <cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="{{ $document['currency_type_id'] }}">{{ $row['total_base_igv'] }}</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="{{ $document['currency_type_id'] }}">{{ $row['total_igv'] }}</cbc:TaxAmount>
                <cac:TaxCategory>
                    <cbc:Percent>{{ $row['percentage_igv'] ?? 18 }}</cbc:Percent>
                    <cbc:TaxExemptionReasonCode>{{ $row['affectation_igv_type_id'] ?? '10' }}</cbc:TaxExemptionReasonCode>
                    <cac:TaxScheme>
                        <cbc:ID>1000</cbc:ID>
                        <cbc:Name>IGV</cbc:Name>
                        <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </cac:TaxSubtotal>
        </cac:TaxTotal>
        <cac:Item>
            <cbc:Description><![CDATA[{{ $row['description'] }}]]></cbc:Description>
        </cac:Item>
        <cac:Price>
            <cbc:PriceAmount currencyID="{{ $document['currency_type_id'] }}">{{ $row['unit_value'] }}</cbc:PriceAmount>
        </cac:Price>
    </cac:InvoiceLine>
    @endforeach
</Invoice>
