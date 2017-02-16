<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>A Component Using External Plugins</title>
</head>
<body>
<script src="https://fb.me/react-15.1.0.js"></script>
<script src="https://fb.me/react-dom-15.1.0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-standalone/6.18.1/babel.min.js"></script>
<script src="https://cdn.jsdelivr.net/remarkable/1.6.2/remarkable.min.js"></script>
<div id="app"></div>
<script type="text/babel">
    class MarkdownEditor extends React.Component {
        constructor(props) {
            super(props);
            this.handleChange = this.handleChange.bind(this);
            this.rawMarkup = this.rawMarkup.bind(this);
            this.state = {
                value: 'Type some *markdown* here!',
            }
        }
        handleChange() {
            this.setState({value: this.refs.textarea.value});
        }
        // 將使用者輸入的 Markdown 語法 parse 成 HTML 放入 DOM 中，React 通常使用 virtual DOM 作為和 DOM 溝通的中介，
        // 不建議直接由操作 DOM。故使用時的屬性為 dangerouslySetInnerHTML
        rawMarkup() {
            const md = new Remarkable();
            return { __html: md.render(this.state.value) };
        }
        render() {
            return (
                <div className="MarkdownEditor">
                    <h3>Input</h3>
                    <textarea
                        onChange={this.handleChange}
                        ref="textarea"
                        defaultValue={this.state.value} />
                    <h3>Output</h3>
                    <div
                        className="content"
                        dangerouslySetInnerHTML={this.rawMarkup()}
                    />
                </div>
            );
        }
    }

    ReactDOM.render(<MarkdownEditor />, document.getElementById('app'));
</script>
</body>
</html>