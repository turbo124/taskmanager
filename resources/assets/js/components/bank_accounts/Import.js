import React from 'react';
import * as XLSX from 'xlsx';
/* https://www.cluemediator.com/read-csv-file-in-react */

export default class Import extends React.Component {
    constructor() {
        super();
        this.state = {
            csvfile: null,
            data: [],
            columns: [],
        };

        this.updateData = this.updateData.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.importCsv = this.importCsv.bind(this);
    }

    handleChange(event) {
        const file = e.target.files[0];
        const reader = new FileReader();
        reader.onload = (evt) => {

            /* Parse data */
            const bstr = evt.target.result;
            const wb = XLSX.read(bstr, {
                type: 'binary'
            });

            /* Get first worksheet */
            const wsname = wb.SheetNames[0];
            const ws = wb.Sheets[wsname];

            /* Convert array of arrays */
            const data = XLSX.utils.sheet_to_csv(ws, {
                header: 1
            });
            processData(data);
        };

        reader.readAsBinaryString(file);

        this.setState({
            csvfile: event.target.files[0]
        })
    }

    importCsv(dataString) {
        const dataStringLines = dataString.split(/\r\n|\n/);
        const headers = dataStringLines[0].split(/,(?![^"]*"(?:(?:[^"]*"){2})*[^"]*$)/);

        const list = [];
        for (let i = 1; i < dataStringLines.length; i++) {
            const row = dataStringLines[i].split(/,(?![^"]*"(?:(?:[^"]*"){2})*[^"]*$)/);
            if (headers && row.length == headers.length) {
                const obj = {};
                for (let j = 0; j < headers.length; j++) {
                    let d = row[j];
                    if (d.length > 0) {
                        if (d[0] == '"')
                            d = d.substring(1, d.length - 1);
                        if (d[d.length - 1] == '"')
                            d = d.substring(d.length - 2, 1);
                    }
                    if (headers[j]) {
                        obj[headers[j]] = d;
                    }
                }

                // remove the blank rows
                if (Object.values(obj).filter(x => x).length > 0) {
                    list.push(obj);
                }
            }
        }

        // prepare columns list from headers
        const columns = headers.map(c => ({
            name: c,
            selector: c,
        }))

        this.setState({
            data: list,
            columns: columns
        })
    }

    save() {

    }

    render() {
        console.log("Render File data: ", this.state.csvfile);
        return ( <div>
            <h2 > Import CSV File! < /h2> 
            <input type = "file"
            accept = ".csv,.xlsx,.xls"
            onChange = {handleChange}
            />

            <button onClick={this.save.bind(this)}> Import now! </button> 
            </div>
        )
    }
}
