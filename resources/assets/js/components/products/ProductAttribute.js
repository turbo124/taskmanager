/* eslint-disable no-unused-vars */
import React from 'react'
import { Input, FormGroup, Label } from 'reactstrap'

class ProductAttribute extends React.Component {
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
        return (
            <React.Fragment>

                <FormGroup>
                    <Label for="length">Length:</Label>
                    <Input value={this.props.product.length}
                        className={this.hasErrorFor('length') ? 'is-invalid' : ''}
                        type="number"
                        name="length" onChange={this.props.handleInput}/>
                    {this.renderErrorFor('length')}
                </FormGroup>

                <FormGroup>
                    <Label for="width">Width:</Label>
                    <Input className={this.hasErrorFor('width') ? 'is-invalid' : ''}
                        value={this.props.product.width}
                        type="number"
                        name="width"
                        onChange={this.props.handleInput}/>
                    {this.renderErrorFor('width')}
                </FormGroup>

                <FormGroup>
                    <Label for="height">Height:</Label>
                    <Input className={this.hasErrorFor('height') ? 'is-invalid' : ''}
                        type="number"
                        name="height"
                        value={this.props.product.height}
                        onChange={this.props.handleInput}/>
                    {this.renderErrorFor('height')}
                </FormGroup>

                <FormGroup>
                    <Label for="distance_unit">Distance Unit:</Label>
                    <Input className={this.hasErrorFor('distance_unit') ? 'is-invalid' : ''}
                        type="select"
                        name="distance_unit"
                        value={this.props.product.distance_unit}
                        onChange={this.props.handleInput}>
                        <option value="">Select Option</option>  
                         <option value="cm">Centimetres</option>
                         <option value="mtr">Meters</option>
                         <option value="in">Inches</option>
                         <option value="mm">Milimeters</option>
                    </Input>
                    {this.renderErrorFor('distance_unit')}
                </FormGroup>

                <FormGroup>
                    <Label for="weight">Weight:</Label>
                    <Input className={this.hasErrorFor('weight') ? 'is-invalid' : ''}
                        type="number"
                        name="weight"
                        value={this.props.product.weight}
                        onChange={this.props.handleInput}/>
                    {this.renderErrorFor('weight')}
                </FormGroup>

                 <FormGroup>
                    <Label for="mass_unit">Mass Unit:</Label>
                    <Input className={this.hasErrorFor('mass_unit') ? 'is-invalid' : ''}
                        type="select"
                        name="mass_unit"
                        value={this.props.product.mass_unit}
                        onChange={this.props.handleInput}>
                        <option value="">Select Option</option>  
                         <option value="oz">Ounces</option>
                         <option value="gms">Grams</option>
                         <option value="lbs">Pounds</option>
                    </Input>
                    {this.renderErrorFor('mass_unit')}
                </FormGroup>
            </React.Fragment>
        )
    }
}

export default ProductAttribute
