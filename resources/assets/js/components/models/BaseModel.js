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

    String getCustomFieldLabel(String field) {
    if (customFields.containsKey(field)) {
      return customFields[field].split('|').first;
    } else {
      return '';
    }
  }

  getCustomFieldType(field) {
    if ((customFields[field] ?? '').contains('|')) {
      final value = customFields[field].split('|').last;
      if ([kFieldTypeSingleLineText, kFieldTypeDate, kFieldTypeSwitch]
          .contains(value)) {
        return value;
      } else {
        return kFieldTypeDropdown;
      }
    } else {
      return kFieldTypeMultiLineText;
    }
  }

  getCustomFieldValues(field, excludeBlank = false) {
    final values = customFields[field];

    if (values == null || !values.contains('|')) {
      return [];
    } else {
      final parts = values.split('|');
      final data = parts.last.split(',');

      if (parts.length == 2) {
        if ([kFieldTypeDate, kFieldTypeSwitch, kFieldTypeSingleLineText]
            .contains(parts[1])) {
          return [];
        }
      }

      if (excludeBlank) {
        return data.where((data) => data.isNotEmpty).toList();
      } else {
        return data;
      }
    }
  }
}
