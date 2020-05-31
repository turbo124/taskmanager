import React from 'react'
import {
    Input,
    FormGroup,
    Label,
    Card,
    CardBody,
    CardHeader, Row, Col
} from 'reactstrap'
import UserDropdown from '../common/UserDropdown'
import CompanyDropdown from '../common/CompanyDropdown'
import CategoryDropdown from '../common/CategoryDropdown'
import FormBuilder from '../accounts/FormBuilder'
import { translations } from '../common/_icons'

export default class DetailsForm extends React.Component {
    constructor (props) {
        super(props)

        this.hasErrorFor = this.hasErrorFor.bind(this)
        this.renderErrorFor = this.renderErrorFor.bind(this)
    }

    hasErrorFor (field) {
        return !!this.props.errors[field]
    }

    renderErrorFor (field) {
        if (this.hasErrorFor(field)) {
            return (
                <span className='invalid-feedback'>
                    <strong>{this.props.errors[field][0]}</strong>
                </span>
            )
        }
    }

    render () {
        const customFields = this.props.custom_fields ? this.props.custom_fields : []

        if (customFields[0] && Object.keys(customFields[0]).length) {
            customFields[0].forEach((element, index, array) => {
                if (this.props[element.name] && this.props[element.name].length) {
                    customFields[0][index].value = this.props[element.name]
                }
            })
        }

        const customForm = customFields && customFields.length ? <FormBuilder
            handleChange={this.props.handleInput}
            formFieldsRows={customFields}
        /> : null
        return (<Card>
            <CardHeader>Product</CardHeader>
            <CardBody>

                <Row form>
                    <Col md={4}>
                        <FormGroup>
                            <Label for="name">{translations.name}(*):</Label>
                            <Input className={this.hasErrorFor('name') ? 'is-invalid' : ''}
                                type="text"
                                name="name"
                                defaultValue={this.props.product.name}
                                onChange={this.props.handleInput}/>
                            {this.renderErrorFor('name')}
                        </FormGroup>
                    </Col>

                    <Col md={4}>
                        <FormGroup>
                            <Label for="email">{translations.description}:</Label>
                            <Input className={this.hasErrorFor('description') ? 'is-invalid' : ''}
                                type="textarea"
                                name="description"
                                defaultValue={this.props.product.description}
                                onChange={this.props.handleInput}/>
                            {this.renderErrorFor('description')}
                        </FormGroup>
                    </Col>

                    <Col md={4}>
                        <FormGroup>
                            <Label for="postcode">{translations.notes}:</Label>
                            <Input
                                value={this.props.product.notes}
                                type='textarea'
                                name="notes"
                                errors={this.props.errors}
                                onChange={this.props.handleInput}
                            />
                        </FormGroup>
                    </Col>

                </Row>

                <Row form>
                    <Col md={3}>
                        <FormGroup>
                            <Label for="sku">Sku(*):</Label>
                            <Input className={this.hasErrorFor('sku') ? 'is-invalid' : ''}
                                type="text"
                                name="sku"
                                value={this.props.product.sku}
                                onChange={this.props.handleInput}/>
                            {this.renderErrorFor('sku')}
                        </FormGroup>
                    </Col>

                    <Col md={2}>
                        <FormGroup>
                            <Label for="email">{translations.quantity}:</Label>
                            <Input className={this.hasErrorFor('quantity') ? 'is-invalid' : ''}
                                type="text"
                                name="quantity"
                                value={this.props.product.quantity}
                                onChange={this.props.handleInput}/>
                            {this.renderErrorFor('quantity')}
                        </FormGroup>
                    </Col>

                    <Col md={2}>
                        <FormGroup>
                            <Label for="price">{translations.price}(*):</Label>
                            <Input className={this.hasErrorFor('price') ? 'is-invalid' : ''}
                                type="text"
                                name="price"
                                defaultValue={this.props.product.price}
                                onChange={this.props.handleInput}/>
                            {this.renderErrorFor('price')}
                        </FormGroup>
                    </Col>

                    <Col md={2}>
                        <FormGroup>
                            <Label for="price">{translations.cost}:</Label>
                            <Input className={this.hasErrorFor('cost') ? 'is-invalid' : ''}
                                type="text"
                                name="cost"
                                defaultValue={this.props.product.cost}
                                onChange={this.props.handleInput}/>
                            {this.renderErrorFor('cost')}
                        </FormGroup>
                    </Col>

                    <Col md={3}>
                        <FormGroup check>
                            <Label check>
                                <Input value={this.props.product.is_featured} onChange={this.props.handleCheck}
                                    type="checkbox"/>
                                {translations.is_featured}
                            </Label>
                        </FormGroup>
                    </Col>
                </Row>

                <Row form>
                    <Col md={4}>
                        <Label for="postcode">{translations.brand}:</Label>
                        <CompanyDropdown
                            name="company_id"
                            company_id={this.props.product.company_id}
                            errors={this.props.errors}
                            handleInputChanges={this.props.handleInput}
                            companies={this.props.companies}
                        />
                    </Col>

                    <Col md={4}>
                        <Label for="postcode">{translations.category}:</Label>
                        <CategoryDropdown
                            multiple={true}
                            name="category"
                            category={this.props.selectedCategories}
                            errors={this.props.errors}
                            handleInputChanges={this.props.handleMultiSelect}
                            categories={this.props.categories}
                        />
                    </Col>

                    <Col md={4}>
                        <FormGroup>
                            <Label for="postcode">{translations.assigned_user}:</Label>
                            <UserDropdown
                                user_id={this.props.product.assigned_user_id}
                                name="assigned_user_id"
                                errors={this.props.errors}
                                handleInputChanges={this.props.handleInput}
                            />
                        </FormGroup>
                    </Col>
                </Row>

                {customForm}

            </CardBody>
        </Card>

        )
    }
}
