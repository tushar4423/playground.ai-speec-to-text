from flask import Flask, render_template, request, redirect, url_for
import assemblyai as aai

app = Flask(__name__)

# Replace with your AssemblyAI API key
aai.settings.api_key = "ec1e8f315ee643e6a7aa1bedf717f33a"

@app.route('/')
def index():
    return render_template('Transcribe.html')

@app.route('/transcribe', methods=['GET'])
def transcribe():
    file_url = request.args.get('file_url')

    if file_url:
        transcriber = aai.Transcriber()
        transcript = transcriber.transcribe(file_url)

        if transcript.status == aai.TranscriptStatus.error:
            error_message = transcript.error
            return render_template('Transcribe.html', error=error_message)
        else:
            transcription_text = transcript.text
            return render_template('Transcribe.html', transcript=transcription_text)
    else:
        error_message = "No file URL provided."
        return render_template('Transcribe.html', error=error_message)

@app.route('/submit', methods=['POST'])
def submit():
    file_url = request.form['file_url']
    return redirect(url_for('transcribe', file_url=file_url))

if __name__ == '__main__':
    app.run(debug=True)
