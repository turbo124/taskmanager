export const LineItem = {
    unit_discount: 0,
    unit_tax: 0,
    quantity: 0,
    unit_price: 0,
    product_id: 0,
    custom_value1: '',
    custom_value2: '',
    custom_value3: '',
    custom_value4: ''
}

export default class BaseModel {
    constructor () {
        this.errors = []
        this.error_message = ''
    }

    handleError (error) {
        if (error.response.data.message) {
            this.error_message = error.response.data.message
        }

        if (error.response.data.errors) {
            this.errors = error.response.data.errors
        }
    }

    isModuleEnabled (module) {
        return JSON.parse(localStorage.getItem('modules'))[module]
    }

    formatCustomValue (value, field}) {
        // need to get custom field settings here
        final CompanyEntity company = state.company;

        switch (company.getCustomFieldType(field)) {
            case kFieldTypeSwitch:
                return value == 'yes' ? translations.yes : translations.no;
            break;
            case kFieldTypeDate:
                return <FormatDate date={value} />
            break;
            default:
                return value;
        }
    }
}
