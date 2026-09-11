<?php echo '<?xml version=""1.0"" encoding=""utf-8"" standalone=""no""?>'; ?>
<VoidedDocuments xmlns=""urn:sunat:names:specification:ubl:peru:schema:xsd:VoidedDocuments-1""
                 xmlns:cac=""urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2""
                 xmlns:cbc=""urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2""
                 xmlns:ext=""urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2""
                 xmlns:sac=""urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1""
                 xmlns:ds=""http://www.w3.org/2000/09/xmldsig#"" xmlns:xsi=""http://www.w3.org/2001/XMLSchema-instance"">
    <ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
    </ext:UBLExtensions>
    <cbc:UBLVersionID>2.0</cbc:UBLVersionID>
    <cbc:CustomizationID>1.0</cbc:CustomizationID>
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
    <sac:VoidedDocumentsLine>
        <cbc:LineID>{{ $loop->iteration }}</cbc:LineID>
        <cbc:DocumentTypeCode>{{ $line['document_type_id'] }}</cbc:DocumentTypeCode>
        <sac:DocumentSerialID>{{ $line['series'] }}</sac:DocumentSerialID>
        <sac:DocumentNumberID>{{ $line['number'] }}</sac:DocumentNumberID>
        <sac:VoidReasonDescription><![CDATA[{{ $line['reason'] }}]]></sac:VoidReasonDescription>
    </sac:VoidedDocumentsLine>
    @endforeach
</VoidedDocuments>
