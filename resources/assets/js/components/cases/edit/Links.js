
import React, { Component } from 'react'
import { FormGroup, Input, Label } from 'reactstrap'
import { translations } from '../../common/_translations'
import ProductDropdown from '../../common/ProductDropdown'
import ProjectDropdown from '../../common/ProjectDropdown'
import { consts } from '../../common/_consts'

export default class Links extends Component {
    render () {
        return (
            <React.Fragment>
                <FormGroup>
                    <Label for="subject">{translations.link_type} <span className="text-danger">*</span></Label>
                    <Input className={this.props.hasErrorFor('link_type') ? 'is-invalid' : ''} type="select"
                        name="link_type"
                        id="link_type" value={this.props.case.link_type}
                        onChange={this.props.handleInput}>
                        <option value="">{translations.select_option}</option>
                        <option value={consts.case_link_type_product}>{translations.product}</option>
                        <option value={consts.case_link_type_project}>{translations.project}</option>
                    </Input>
                    {this.props.renderErrorFor('link_type')}
                </FormGroup>

                {parseInt(this.props.case.link_type) === consts.case_link_type_project &&
                <FormGroup>
                    <Label for="message">{translations.project}<span className="text-danger">*</span></Label>
                    <ProjectDropdown
                        renderErrorFor={this.renderErrorFor}
                        name="link_value"
                        handleInputChanges={this.props.handleInput}
                        project={this.props.case.link_value}
                    />
                </FormGroup>
                }

                {parseInt(this.props.case.link_type) === consts.case_link_type_product &&
                <FormGroup>
                    <Label for="message">{translations.product}<span className="text-danger">*</span></Label>
                    <ProductDropdown
                        renderErrorFor={this.renderErrorFor}
                        name="link_value"
                        handleInputChanges={this.props.handleInput}
                        product={this.props.case.link_value}
                        products={this.props.products}
                    />
                </FormGroup>
                }
            </React.Fragment>
        )
    }
}
