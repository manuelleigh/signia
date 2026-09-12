<?php echo '<?xml version=""1.0"" encoding=""utf-8"" standalone=""no""?>'; ?>
<SummaryDocuments
        xmlns=""urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1""
        xmlns:cac=""urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2""
        xmlns:cbc=""urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2""
        xmlns:ds=""http://www.w3.org/2000/09/xmldsig#""
        xmlns:ext=""urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2""
        xmlns:sac=""urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1""
        xmlns:xsi=""http://www.w3.org/2001/XMLSchema-instance"">
    <ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
    </ext:UBLExtensions>
    <cbc:UBLVersionID>2.0</cbc:UBLVersionID>
    <cbc:CustomizationID>1.1</cbc:CustomizationID>
    <cbc:ID>{{ $document['series'] }}-{{ $document['number'] }}</cbc:ID>
    <cbc:ReferenceDate>{{ $document['reference_date'] }}</cbc:ReferenceDate>
    <cbc:IssueDate>{{ $document['date_of_issue'] }}</cbc:IssueDate>
    <cac:Signature>
        <cbc:ID>IDSignia</cbc:ID>
        <cac:SignatoryParty>
            <cac:PartyIdentification>
                <cbc:ID>{{ $company['ruc'] }}</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyName>
                <cbc:Name><![CDATA[{{ $company['business_name'] }}]]></cbc:Name>
            </cac:PartyName>
        </cac:SignatoryParty>
        <cac:DigitalSignatureAttachment>
            <cac:ExternalReference>
                <cbc:URI>#IDSignia</cbc:URI>
            </cac:ExternalReference>
        </cac:DigitalSignatureAttachment>
    </cac:Signature>
    <cac:AccountingSupplierParty>
        <cbc:CustomerAssignedAccountID>{{ $company['ruc'] }}</cbc:CustomerAssignedAccountID>
        <cbc:AdditionalAccountID>6</cbc:AdditionalAccountID>
        <cac:Party>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA[{{ $company['business_name'] }}]]></cbc:RegistrationName>
            </cac:PartyLegalEntity>
        </cac:Party>
    </cac:AccountingSupplierParty>
    @foreach($document['lines'] as $line)
    <sac:SummaryDocumentsLine>
        <cbc:LineID>{{ $loop->iteration }}</cbc:LineID>
        <cbc:DocumentTypeCode>{{ $line['document_type_id'] }}</cbc:DocumentTypeCode>
        <cbc:ID>{{ $line['series'] }}-{{ $line['number'] }}</cbc:ID>
        <cac:AccountingCustomerParty>
            <cbc:CustomerAssignedAccountID>{{ $line['customer_number'] ?? '00000000' }}</cbc:CustomerAssignedAccountID>
            <cbc:AdditionalAccountID>{{ $line['customer_identity_type'] ?? '1' }}</cbc:AdditionalAccountID>
        </cac:AccountingCustomerParty>
        <cac:Status>
            <cbc:ConditionCode>{{ $line['condition_code'] ?? '1' }}</cbc:ConditionCode>
        </cac:Status>
        <sac:TotalAmount currencyID="PEN">{{ $line['total'] }}</sac:TotalAmount>
        <sac:BillingPayment>
            <cbc:PaidAmount currencyID="PEN">{{ $line['total_taxed'] ?? 0 }}</cbc:PaidAmount>
            <cbc:InstructionID>01</cbc:InstructionID>
        </sac:BillingPayment>
        <cac:TaxTotal>
            <cbc:TaxAmount currencyID="PEN">{{ $line['total_igv'] ?? 0 }}</cbc:TaxAmount>
            <cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="PEN">{{ $line['total_taxed'] ?? 0 }}</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="PEN">{{ $line['total_igv'] ?? 0 }}</cbc:TaxAmount>
                <cac:TaxCategory>
                    <cac:TaxScheme>
                        <cbc:ID>1000</cbc:ID>
                        <cbc:Name>IGV</cbc:Name>
                        <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </cac:TaxSubtotal>
        </cac:TaxTotal>
    </sac:SummaryDocumentsLine>
    @endforeach
</SummaryDocuments>
