<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Models\ImportQuestion;
use App\Models\Qbank;
use App\Models\QbankQuestion;
use App\Models\Rating;
use App\Traits\ValidationsTrait;
use DOMDocument;
use DOMXPath;
use Exception;
use File;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use Maatwebsite\Excel\Facades\Excel;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Str;
use Validator;
use ZipArchive;

class QbankController extends BaseController
{
    use ValidationsTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->input('filter')){
            $qbankList = Qbank::query();
            if($request->search){
                $qbankList = $qbankList->where('title', 'LIKE', "%$request->search%");
            }
        }else{
            $qbankList = Qbank::query();
        }
        $qbankList = $qbankList->where('organization_id', lms_organization_id());
        $qbankList = $qbankList->orderBy('title', 'asc')
                ->paginate(lms_setting('admin_pagination_size'))
                ->appends($request->query());
        
        return view('admin.qbank.index', compact('qbankList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.qbank.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            $this->getQbankAddRules(0),
            $this->getQbankAddMessages()
        );
        $data['status'] = 1;
        $qbank = Qbank::create($data);
        if($request->save_and_new){
            return back()->with('alert', generate_alert(__('Question bank created')));
        }
        return to_route('admin.qbank.index')->with('alert', generate_alert(__('Question bank created')));
    }

    /**
     * Display the specified resource.
     */
    public function show(Qbank $qbank)
    {
        return 'show';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Qbank $qbank)
    {
        $this->getQbank($qbank->id);
        return view('admin.qbank.edit', compact('qbank'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Qbank $qbank)
    {
        $qbank = $this->getQbank($qbank->id);
        // Validation begins
        $data = $request->validate(
            $this->getQbankAddRules($qbank->id),
            $this->getQbankAddMessages()
        );
        $qbank->title = $data['title'];
        $qbank->description = $data['description'] ?? '';
        $qbank->save();
        
        return redirect()->route('admin.qbank.index')->with('alert', generate_alert(__('Question Bank updated')));
    }
    
    /**
     * Activate / Deactivate an Question Bank.
     */
    public function activate($qbankId, $status)
    {
        $qbank = $this->getQbank($qbankId);
        if($qbank){
            $qbank->status = $status == 1 ? 1 : 0;
            $qbank->save();
            return back()->with('alert', generate_alert(__('Question Bank updated')));
        }else{
            return back()->with('alert', generate_alert(__('Question Bank not available'), 'danger'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Qbank $qbank)
    {
        $qbank = $this->getQbank($qbank->id);
        try {
            $qbank->delete();
            return redirect()->route('admin.qbank.index')->with('alert', generate_alert(__('Question Bank deleted')));
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return back()->with('alert', generate_alert(__('This record cannot be deleted because it is linked to other data.'), 'danger'));
            }
            throw $e;
        }
    }

    public function questions($qbankId)
    {
        $qbank = $this->getQbank($qbankId);
        $questions = QbankQuestion::where([
            ['qbank_id', $qbankId]
        ])->paginate(lms_setting('admin_pagination_size'));
        $counts = [];
        $counts['all_questions'] = QbankQuestion::where('qbank_id', $qbankId)->count();        
        $counts['active_questions'] = QbankQuestion::where('qbank_id', $qbankId)->where('status', 1)->count();        
        $counts['inactive_questions'] = QbankQuestion::where('qbank_id', $qbankId)->where('status', 0)->count();        
        return view('admin.qbank.questions', compact('qbank', 'questions', 'counts'));
    }

    public function create_question($qbankId){
        $qbank = $this->getQbank($qbankId);
        return view('admin.qbank.create-question', compact('qbank'));
    }

    public function edit_question($qbankId, $id){
        $qbank = $this->getQbank($qbankId);
        if(!$qbank){
            abort(404, 'Question Bank not found');
        }
        $question = QbankQuestion::where([
            ['id', $id],
            ['qbank_id', $qbankId]
        ])->first();
        return view('admin.qbank.edit-question', compact('qbank', 'question'));
    }

    /**
     * Save course question.
     */
    public function save_question(Request $request, $qbankId)
    {
        $qbank = $this->getQbank($qbankId);
        $data = $request->validate(
            $this->getQbankQuestionAddRules(),
            $this->getQbankQuestionAddMessages()
        );
        
        $id = $request->id;
        $questionType = $data['type'];
        if($questionType == 'multiple'){
            $delimiter = config('constants.ANSWER_DELIMITER');
            $data['options'] = $data['multiple_options'];
            $data['answer'] = implode($delimiter, $data['multiple_answers']);
            unset($data['multiple_options'], $data['multiple_answers']);
        }else if($questionType == 'fill'){
            $data['answer'] = $data['fill_answer'];
            unset($data['fill_answer']);
        }else if($questionType == 'yesno'){
            $data['answer'] = $data['yesno'];
            unset($data['yesno']);
        }

        if($id){
            $question = QbankQuestion::where([
                ['qbank_id', $qbankId],
                ['id', $id]
            ])->first();
            if(!$question){
                abort(404);
            }
            //$data['title'] = preg_replace('/\s+/', ' ', $data['title']);
            $question->title = $data['title'];
            $question->description = $request->description ?? '';
            $question->type = $data['type'];
            $question->answer = $data['answer'];
            $question->options = $data['options'] ?? '';
            $question->marks = 0;
            $question->save();
        }else{
            $data['marks'] = 0;
            $data['status'] = 1;
            $data['qbank_id'] = $qbankId;
            $data['description'] = $request->description ?? '';
            $question = QbankQuestion::create($data);
        }        

        return to_route('admin.qbank.questions', ['qbankId' => $qbankId])->with('alert', generate_alert(__('Question saved')));
    }
    
    /**
     * Activate / Deactivate an question.
     */
    public function activate_question($qbankId, $questionId, $status)
    {
        $qbank = $this->getQbank($qbankId);
        $question = QbankQuestion::where([
            ['id', $questionId],
            ['qbank_id', $qbankId]
        ])->first();
        if($question){
            $question->status = $status == 1 ? 1 : 0;
            $question->save();
            return back()->with('alert', generate_alert(__('Question updated')));
        }else{
            return back()->with('alert', generate_alert(__('Question not available'), 'danger'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete_question($qbankId, $questionId)
    {
        $qbank = $this->getQbank($qbankId);
        try {
            QbankQuestion::where([
                ['qbank_id', $qbankId],
                ['id', $questionId]
            ])->delete();
            return back()->with('alert', generate_alert(__('Question Bank deleted')));
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return back()->with('alert', generate_alert(__('This record cannot be deleted because it is linked to other data.'), 'danger'));
            }
            throw $e;
        }
    }

    private function extractQuestionsFromWord($filePath, $qbankId){
        $convertedFilePath = $this->convertMathMLToPlainText($filePath);
        if($convertedFilePath === -1){
            return back()->with('alert', generate_alert(__('Failed to convert MathML to plain text.'), 'danger'));
        }else if($convertedFilePath !== 0){
            $filePath = $convertedFilePath;
        }
        $phpWord = IOFactory::load($filePath);
        $questions = [];

        $text = $this->parseExamContentFromWord($phpWord, $qbankId);

        //file_put_contents('s.txt', $text);
        //p($text);
        //$pattern = "/Q: (.*?)\\n(A1\\. (.*?))(?:\\n(A2\\. (.*?)))?(?:\\n(A3\\. (.*?)))?(?:\\n(A4\\. (.*?)))?\\nCorrect Answer: (A\\d)\\nExplanation:\\n(.*?)\\n\\n?/s";
        //$pattern = "/Q: (.*?)\\n(a\\. (.*?))(?:\\n(b\\. (.*?)))?(?:\\n(c\\. (.*?)))?(?:\\n(d\\. (.*?)))?(?:\\n(e\\. (.*?)))?\\nCorrect Answer: (a|b|c|d|e)\\nExplanation:\\n(.*?)\\n\\n?/s";
        //$pattern = "/Q: (.*?)\\n(a\\. (.*?))(?:\\n(b\\. (.*?)))?(?:\\n(c\\. (.*?)))?(?:\\n(d\\. (.*?)))?(?:\\n(e\\. (.*?)))?\\nCorrect Answer: (a|b|c|d|e)(?:\\nExplanation:\\n(.*?))?\\n\\n?/s";
        //$pattern = "/Q: (.*?)\n(a\. (.*?))\n(b\. (.*?))?\n(c\. (.*?))?\n(d\. (.*?))?\n(e\. (.*?))?\nCorrect Answer: ([a-e])\nExplanation:\n(.*?)(?=\nQ:|\z)/s";
        // 10-03-2025
        //$pattern = "/Q: (.*?)\\n(a\\. (.*?))\\n(b\\. (.*?))?\\n(c\\. (.*?))?\\n?(d\\. (.*?))?\\n?(e\\. (.*?))?\\n?Correct Answer: ([a-e])\\nExplanation:\\n((?:.|\\n)*?)(?:\\n{1,4}|$)/s";
        // 11-03-2025
        //$pattern = "/Q: ?(.*?)\\n(a\\. (.*?))\\n(b\\. (.*?))?\\n(c\\. (.*?))?\\n?(d\\. (.*?))?\\n?(e\\. (.*?))?\\n?Correct Answer: ([a-e])(?:\\nExplanation:\\n((?:.*(?:\\n(?!Q:))*)?))?(?:\\n{1,4}|$)/s";
        // 13-03-2025
        //$pattern = '/Q: ?(.*?)\n([aA]\.\s*(.*?))\n([bB]\.\s*(.*?))?\n?([cC]\.\s*(.*?))?\n?([dD]\.\s*(.*?))?\n?([eE]\.\s*(.*?))?\n?Answer:\s*(?:\W*)?([a-eA-E])(?:\nExplanation:\n((?:(?!\nQ:).)*))?/s';
        //17-03-2025
        //$pattern = '/Q: ?(.*?)\n([aA]\.\s*(.*?))(?:\n([bB]\.\s*(.*?)))?(?:\n([cC]\.\s*(.*?)))?(?:\n([dD]\.\s*(.*?)))?(?:\n([eE]\.\s*(.*?)))?\n?Answer:\s*(?:\W*)?([a-eA-E])(?:\nExplanation:\n((?:(?!\nQ:).)*))?/s';
        //29-08-2025
        $pattern = '/Q: ?(.*?)\n([aA][\.\)]\s*(.*?))(?:\n([bB][\.\)]\s*(.*?)))?(?:\n([cC][\.\)]\s*(.*?)))?(?:\n([dD][\.\)]\s*(.*?)))?(?:\n([eE][\.\)]\s*(.*?)))?\n?Answer:\s*(?:\W*)?([a-eA-E])(?:\nExplanation:\n((?:(?!\nQ:).)*))?/s';
        preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);

        $questions = [];

        foreach ($matches as $match) {
            
            // Trim all matches to remove extra spaces
            $match = array_map('trim', $match);

            // Build the answer options dynamically
            $answerMap = [
                "a" => $match[3] ?? '',
                "b" => $match[5] ?? '',
                "c" => $match[7] ?? '',
                "d" => $match[9] ?? '',
                "e" => $match[11] ?? '',
            ];

            $correctAnswerKey = strtolower($match[12]);
            $correctAnswerKey = trim(preg_replace('/\s+/', ' ', $correctAnswerKey));
            $correctAnswerText = $answerMap[$correctAnswerKey] ?? '';
            $explanation = trim($match[13] ?? '');
            /*if(strtolower($explanation) == 'not available'){
                $explanation = '';
            }*/

            $questions[] = [
                'question' => trim($match[1]),
                'option_a' => lms_custom_trim($answerMap['a']),
                'option_b' => lms_custom_trim($answerMap['b']),
                'option_c' => lms_custom_trim($answerMap['c']),
                'option_d' => lms_custom_trim($answerMap['d']),
                'option_e' => lms_custom_trim($answerMap['e']),
                'correct_answer' => lms_custom_trim($correctAnswerText),
                'explanation' => $explanation,
            ];
        }
        //p($questions);

        return $questions;
    }

    public function import_questions($qbankId)
    {
        $qbank = $this->getQbank($qbankId);
        return view('admin.qbank.import-questions', compact('qbank'));
    }

    public function delete_import_questions($qbankId, $id)
    {
        $question = ImportQuestion::where([
            ['qbank_id', $qbankId],
            ['id', $id]
        ])->delete();
        return back()->with('alert', generate_alert(__('Question deleted')));
    }

    public function upload_questions(Request $request, $qbankId)
    {
        $qbank = $this->getQbank($qbankId);

        $validator = Validator::make($request->all(), [
            //'file' => ['required', 'image|mimes:docx|max:10240'], // 10 MB
            'file' => ['required', 'mimetypes:application/vnd.openxmlformats-officedocument.wordprocessingml.document,text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'max:20480'],
        ], [
            'file' => [
                'required' => 'Please upload question file',
                'mimetypes' => 'Only docx / xlsx formats are allowed.',
                'max' => 'The file size should not exceed 10 MB.',
            ]
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Disable unprocessed import questions
        ImportQuestion::where([
            ['qbank_id', $qbankId],
            ['status', 0]
        ])->update([
            'status' => 1
        ]);

        $path = '';
        $image = $request->file('file');
        if($image){
            $path = $image->store("import/qbank/$qbank->id", 'public');
        }

        $filePath = "storage/$path";
        if(file_exists($filePath)){
            if($image->getClientOriginalExtension() == 'xlsx'){
                $data = Excel::toArray([], $filePath);
                $excelHeaders = ['Question', 'Answer 1', 'Answer 2', 'Answer 3', 'Answer 4', 'Correct Answer', 'Explanation'];
                $questions = [];
                foreach ($data[0] as $r_index => &$row) {
                    $row = lms_array_filter(array_map('trim', $row)); // Removes empty elements
                    if($r_index == 0){
                        if($row != $excelHeaders){
                            return to_route('admin.qbank.import-questions', ['qbankId' => $qbankId])->with('alert', generate_alert(__('Please ensure that you are using correct file format'), 'danger'));
                        }
                        continue;
                    }
                    $questions []= [
                        'question' => lms_custom_trim($row[0]),
                        'option_a' => lms_custom_trim($row[1]),
                        'option_b' => lms_custom_trim($row[2]),
                        'option_c' => lms_custom_trim($row[3]),
                        'option_d' => lms_custom_trim($row[4]),
                        'correct_answer' => lms_custom_trim($row[5]),
                        'explanation' => $row[6] ?? '',
                    ];
                }
            }else{
                //try {
                    $questions = $this->extractQuestionsFromWord($filePath, $qbankId);
                    if (!is_array($questions) && $questions instanceof \Illuminate\Http\RedirectResponse) {
                        return back()->with('alert', session('alert'));    
                    }
                    if(empty($questions)){
                        return back()->with('alert', generate_alert(__('Data could not be parsed. Please make sure the format is correct and try again'), 'danger'));
                    }
                /*} catch (Throwable $th) {
                    return back()->with('alert', generate_alert(__('Unknown Error. Please try to with fewer questions ' . str_replace('`', '', $th->getMessage())), 'danger'));
                }*/
            }
            //dd($questions);
            $grouping = Str::random(20);
            $questions = array_map(function ($item) use ($qbankId, $grouping) {
                $item['grouping'] = $grouping;
                $item['qbank_id'] = $qbankId;
                $item['created_at'] = now(); // Add timestamps if needed
                $item['updated_at'] = now();
                return $item;
            }, $questions);
            
            ImportQuestion::insert($questions);
            return to_route('admin.qbank.review_questions', ['qbankId' => $qbankId])->with('alert', generate_alert(__('Question extracted from document. Please review questions details')));
        }
        return back()->with('alert', generate_alert(__('File is missing'), 'danger'));
    }

    public function disable_bulk_questions($qbankId)
    {
        $qbank = $this->getQbank($qbankId);
        ImportQuestion::where([
            ['qbank_id', $qbankId],
            ['status', 0]
        ])->update([
            'status' => 1
        ]);
        return to_route('admin.qbank.import-questions', ['qbankId' => $qbankId]);
    }

    public function review_questions($qbankId)
    {
        $qbank = $this->getQbank($qbankId);
        $questions = ImportQuestion::where([
            ['status', 0],
            ['qbank_id', $qbankId]
        ])->get();
        if(count($questions) == 0){
            return to_route('admin.qbank.index');
        }

        $duplicates = 0;
        /*$questions = $questions->map(function ($item, $key) use ($questions) {
            // Count how many times the question appears
            $duplicates = $questions->where('question', $item->question)->count();

            // Add virtual field
            $item->is_duplicate = $duplicates > 1;
            if($item->is_duplicate) $duplicates++;

            return $item;
        });*/
        return view('admin.qbank.review-questions', compact('qbank', 'questions', 'duplicates'));
    }

    public function save_bulk_questions(Request $request, $qbankId)
    {
        $qbank = $this->getQbank($qbankId);

        /*$validator = Validator::make($request->all(), [
            'marks_per_question' => ['required'], // 10 MB
            'total_mark' => ['required', 'integer', 'min:1'],
        ], [
            'total_mark' => [
                'required' => 'New Total marks are required.',
                'integer' => 'New Total marks must be an integer.',
                'min' => 'New Total marks must be at least 1.',
            ],
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }*/
        
        $questions = ImportQuestion::where([
            ['qbank_id', $qbankId],
            ['status', 0]
        ])->get();

        $qbank_questions = [];
        foreach ($questions as $key => $q) {
            $c_question = [
                'qbank_id' => $qbankId,
                'type' => 'multiple',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $options = [];
            if(isset($q['option_a']) && $q['option_a'] !== '') $options []= $q['option_a'];
            if(isset($q['option_b']) && $q['option_b'] !== '') $options []= $q['option_b'];
            if(isset($q['option_c']) && $q['option_c'] !== '') $options []= $q['option_c'];
            if(isset($q['option_d']) && $q['option_d'] !== '') $options []= $q['option_d'];
            if(isset($q['option_e']) && $q['option_e'] !== '') $options []= $q['option_e'];
            
            $c_question['options'] = implode(config('constants.ANSWER_DELIMITER'), $options);
            $c_question['title'] = $q['question'];
            $c_question['answer'] = $q['correct_answer'];
            $c_question['description'] = $q['explanation'];
            $c_question['marks'] = 0;
            $c_question['status'] = 1;
            $qbank_questions []= $c_question;
        }
        QbankQuestion::insert($qbank_questions);
        ImportQuestion::where([
            ['qbank_id', $qbankId],
            ['status', 0]
        ])->update([
            'status' => 1
        ]);
        $qbank->save();
        return to_route('admin.qbank.questions', ['qbankId' => $qbankId])->with('alert', generate_alert(__('Questions imported successfully')));
    }

    private function convertMathMLToPlainText_old($filePath) {
        $zip = new ZipArchive;
        $tempPath = tempnam(sys_get_temp_dir(), 'converted_') . '.docx';

        if ($zip->open($filePath) === TRUE) {
            $xmlContent = $zip->getFromName('word/document.xml');

            if (strpos($xmlContent, '<m:oMath') === false) return 0;

            // Replace <m:oMath> blocks with <w:r> style text runs
            $xmlContent = preg_replace_callback('/<m:oMath[^>]*>.*?<m:t>\s*(.*?)\s*<\/m:t>.*?<\/m:oMath>/s', function ($matches) {
                $letter = trim($matches[1]);

                // Replace with <w:r> styled text run
                return <<<XML
<w:r>
<w:rPr>
    <w:rFonts w:ascii="Cambria Math" w:hAnsi="Cambria Math"/>
    <w:color w:val="000000" w:themeColor="text1"/>
</w:rPr>
<w:t xml:space="preserve"> {$letter} </w:t>
</w:r>
XML;
            }, $xmlContent);

            // Replace in ZIP and save
            $zip->deleteName('word/document.xml');
            $zip->addFromString('word/document.xml', $xmlContent);
            $zip->close();

            copy($filePath, $tempPath);
            return $tempPath;
        }

        return -1;
    }

    private function convertMathMLToPlainText($filePath)
    {
        $zip = new ZipArchive;
        $tempPath = tempnam(sys_get_temp_dir(), 'converted_') . '.docx';

        if ($zip->open($filePath) === TRUE) {
            $xmlContent = $zip->getFromName('word/document.xml');

            /*if (strpos($xmlContent, 'xmlns:m=') === false) {
                $xmlContent = preg_replace(
                    '/<w:document/',
                    '<w:document xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"',
                    $xmlContent,
                    1
                );
            }*/

            if (strpos($xmlContent, '<m:oMath') === false) {
                return 0;
            }

            // Load XML safely
            $dom = new DOMDocument();
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            libxml_use_internal_errors(true);
            $dom->loadXML($xmlContent);

            $xpath = new DOMXPath($dom);
            $xpath->registerNamespace('m', 'http://schemas.openxmlformats.org/officeDocument/2006/math');
            $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

            // Get all oMath elements
            $mathNodes = $xpath->query('//m:oMath');

            foreach ($mathNodes as $mathNode) {
                // Get all text parts inside the Math node
                $textNodes = $xpath->query('.//m:t', $mathNode);
                $plainText = '';
                foreach ($textNodes as $tNode) {
                    $plainText .= $tNode->textContent . ' ';
                }
                $plainText = trim($plainText);

                // Create new <w:r><w:t> node
                $w_r = $dom->createElement('w:r');

                $w_rPr = $dom->createElement('w:rPr');
                $w_fonts = $dom->createElement('w:rFonts');
                $w_fonts->setAttribute('w:ascii', 'Cambria Math');
                $w_fonts->setAttribute('w:hAnsi', 'Cambria Math');
                $w_rPr->appendChild($w_fonts);

                $w_color = $dom->createElement('w:color');
                $w_color->setAttribute('w:val', '000000');
                $w_color->setAttribute('w:themeColor', 'text1');
                $w_rPr->appendChild($w_color);
                $w_r->appendChild($w_rPr);

                $w_t = $dom->createElement('w:t', htmlspecialchars($plainText));
                $w_t->setAttribute('xml:space', 'preserve');
                $w_r->appendChild($w_t);

                // Wrap in <w:p> for valid structure
                $w_p = $dom->createElement('w:p');
                $w_p->appendChild($w_r);

                // Replace original <m:oMath> with the plain <w:p>
                $mathNode->parentNode->replaceChild($w_p, $mathNode);
            }

            // Save back to ZIP
            $xmlContent = $dom->saveXML();
            $zip->deleteName('word/document.xml');
            $zip->addFromString('word/document.xml', $xmlContent);
            $zip->close();

            copy($filePath, $tempPath);
            return $tempPath;
        }

        return -1;
    }

    private function extractContentFromTable($table, &$text, $qbankId)
    {
        foreach ($table->getRows() as $row) {
            foreach ($row->getCells() as $cell) {
                foreach ($cell->getElements() as $child) {
                    //echo " ------------ Child Element Type: " . get_class($child) . "<br>";
                    // Check for nested tables
                    if ($child instanceof \PhpOffice\PhpWord\Element\Table) {
                        // Recursively handle nested tables
                        $this->extractContentFromTable($child, $text, $qbankId);
                    }

                    // Handle TextRun
                    elseif ($child instanceof \PhpOffice\PhpWord\Element\TextRun) {
                        foreach ($child->getElements() as $textElement) {
                            //echo " ------------ Child Child Element Type: " . get_class($textElement) . "<br>";
                            //echo " Table TextRun TTTEXT : " . $textElement->getText() . '<br>';
                            if ($textElement instanceof \PhpOffice\PhpWord\Element\Text) {
                                $text .= preg_replace("/\t+$/m", "", $textElement->getText());
                            } elseif ($textElement instanceof \PhpOffice\PhpWord\Element\TextBreak) {
                                $text .= "\n";
                            } elseif ($textElement instanceof \PhpOffice\PhpWord\Element\Image) {
                                $this->extractImageFromElement($textElement, $text, $qbankId);
                            }
                        }
                        $text .= "\n";
                    }

                    // Optionally handle single Text (not in TextRun)
                    elseif ($child instanceof \PhpOffice\PhpWord\Element\Text) {
                        //echo " Table Text TTTEXT : " . $child->getText() . '<br>';
                        $text .= preg_replace("/\t+$/m", "", $child->getText()) . "\n";
                    }

                    elseif ($child instanceof \PhpOffice\PhpWord\Element\TextBreak) {
                        $text .= "\n"; 
                    }

                    elseif ($child instanceof \PhpOffice\PhpWord\Element\Image) {
                        $this->extractImageFromElement($child, $text, $qbankId);
                    }

                    /*elseif ($child instanceof \PhpOffice\PhpWord\Element\StructuredDocumentTag) {
                        foreach ($child->getElements() as $inner) {
                            if ($inner instanceof \PhpOffice\PhpWord\Element\Text) {
                                $text .= preg_replace("/\t+$/m", "", $inner->getText());
                            } elseif ($inner instanceof \PhpOffice\PhpWord\Element\TextRun) {
                                foreach ($inner->getElements() as $textElement) {
                                    if ($textElement instanceof \PhpOffice\PhpWord\Element\Text) {
                                        $text .= preg_replace("/\t+$/m", "", $textElement->getText());
                                    }
                                }
                            }
                        }
                        $text .= "\n";
                    }*/
                }
            }
        }
    }

    private function extractImageFromElement($child, &$text, $qbankId){
        // Skip unsupported image formats like EMF
        $source = $child->getSource();
        $originalExtension = pathinfo($source, PATHINFO_EXTENSION);
        if (in_array(strtolower($originalExtension), ['emf', 'wmf'])) {
            return false;
        }
        $imageData = file_get_contents($child->getSource());
        $extension = pathinfo($child->getSource(), PATHINFO_EXTENSION) ?: 'jpg';
        $filename = "qbank_images/$qbankId/" . uniqid() . '.' . $extension;
        Storage::disk('public')->put($filename, $imageData);
        [$width, $height] = getimagesize("storage/$filename");
        $c_url = asset("storage/$filename");
        $style = $height > 120 ? 'display: block;' : '';
        //max-height: 120px;  margin-bottom: 10px;
        $text .= "\n <img src=\"" . $c_url . "\" style=\"$style\" />\n"; // Append image URL with a newline
    }

    private function parseExamContentFromWord($phpWord, $qbankId){
        $text = ""; // Initialize text variable
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                //echo "Element Type: " . get_class($element) . "<br>";
                /*if ($element instanceof \PhpOffice\PhpWord\Element\ListItemRun) {
                    foreach ($element->getElements() as $ic => $child) {
                        if ($child instanceof \PhpOffice\PhpWord\Element\Text) {
                            $text .= preg_replace("/\t+$/m", "", $child->getText());
                        } elseif ($child instanceof \PhpOffice\PhpWord\Element\TextBreak) {
                            $text .= "\n";
                        } elseif ($child instanceof \PhpOffice\PhpWord\Element\Image) {
                            $this->extractImageFromElement($child, $text, $qbankId);
                        }
                    }
                    $text .= "\n";
        
                } else*/if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    foreach ($element->getElements() as $ic => $child) {
                        //echo " ------------ Child Element Type: " . get_class($child) . "<br>";
                        //echo " TextRun TTTEXT : " . $child->getText() . '<br>';
                        if ($child instanceof \PhpOffice\PhpWord\Element\Text) {
                            // Append text properly without adding extra newlines
                            //$text .= $child->getText();
                            //$text .= trim(preg_replace('/\s+/', ' ', $child->getText()));
                            $text .= preg_replace("/\t+$/m", "", $child->getText());
                        } elseif ($child instanceof \PhpOffice\PhpWord\Element\TextBreak) {
                            // Handle soft returns (↵)
                            $text .= "\n"; 
                        } elseif ($child instanceof \PhpOffice\PhpWord\Element\Image) {
                            $this->extractImageFromElement($child, $text, $qbankId);
                        }
                    }
                    $text .= "\n"; // Ensure proper paragraph separation
                } elseif ($element instanceof \PhpOffice\PhpWord\Element\Paragraph) {
                    foreach ($element->getElements() as $child) {
                        //echo " Text TTTEXT : " . $child->getText() . '<br>';
                        if ($child instanceof \PhpOffice\PhpWord\Element\Text) {
                            $text .= preg_replace("/\t+$/m", "", $child->getText());
                        }
                    }
                    $text .= "\n";
                } elseif ($element instanceof \PhpOffice\PhpWord\Element\Table) {
                    $this->extractContentFromTable($element, $text, $qbankId);
                }

                /*elseif ($element instanceof \PhpOffice\PhpWord\Element\StructuredDocumentTag) {
                    foreach ($element->getElements() as $inner) {
                        if ($inner instanceof \PhpOffice\PhpWord\Element\Text) {
                            $text .= preg_replace("/\t+$/m", "", $inner->getText());
                        } elseif ($inner instanceof \PhpOffice\PhpWord\Element\TextRun) {
                            foreach ($inner->getElements() as $textElement) {
                                if ($textElement instanceof \PhpOffice\PhpWord\Element\Text) {
                                    $text .= preg_replace("/\t+$/m", "", $textElement->getText());
                                }
                            }
                        }
                    }
                    $text .= "\n";
                }*/
            }
        }
        return trim($text);
    }

    private function removeEmfImagesFromDocx($originalPath) {
        $zip = new ZipArchive;
        if ($zip->open($originalPath) === TRUE) {
            $filesToDelete = [];
    
            // Collect all .emf files inside the archive
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entryName = $zip->getNameIndex($i);
                //echo "$entryName <br>";
                if (preg_match('/^word\/media\/.*\.emf$/i', $entryName)) {
                    $filesToDelete[] = $entryName;
                }
            }
    
            // Delete all matching files
            foreach ($filesToDelete as $file) {
                $zip->deleteName($file);
            }
    
            $zip->close();
            return true;
        } else {
            throw new Exception("Unable to open DOCX file for modification.");
        }
    }

    private function cleanEmfFromDocx($sourceDocx) {
        $tmpDir = sys_get_temp_dir() . '/docx_unzip_' . uniqid();
        //$outputDocx = "import/qbanks/$qbankId/" . uniqid() . '.docx';
        $userId = lms_user_id();
        $path = "app/public/import/qbanks/$userId.docx";
        $outputDocx = storage_path($path);
        //Storage::disk('public')->put($outputDocx, $imageData);
    
        $tmpDir = storage_path('app/tmp_unzip_' . uniqid());

        // Step 1: Unzip DOCX
        $zip = new ZipArchive;
        if ($zip->open($sourceDocx) === TRUE) {
            mkdir($tmpDir);
            $zip->extractTo($tmpDir);
            $zip->close();
        } else {
            throw new Exception("Unable to open DOCX.");
        }

        // Step 2: Delete .emf images
        $mediaDir = $tmpDir . '/word/media/';
        if (is_dir($mediaDir)) {
            $files = scandir($mediaDir);
            foreach ($files as $file) {
                if (str_ends_with(strtolower($file), '.emf')) {
                    unlink($mediaDir . $file);
                }
            }
        }

        // Step 3: Remove references in document.xml.rels
        $relsFile = $tmpDir . '/word/_rels/document.xml.rels';
        if (file_exists($relsFile)) {
            $xml = simplexml_load_file($relsFile);
            foreach ($xml->Relationship as $rel) {
                if (str_ends_with(strtolower((string)$rel['Target']), '.emf')) {
                    unset($rel[0]);
                }
            }
            $xml->asXML($relsFile);
        }

        // Step 4: Rebuild DOCX
        $zip = new ZipArchive;
        if ($zip->open($outputDocx, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($tmpDir),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($tmpDir) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        } else {
            throw new Exception("Unable to create cleaned DOCX.");
        }

        // Cleanup temp folder
        File::deleteDirectory($tmpDir);
        return $outputDocx;
    }

    private function alertEmfFileError($docxFilePath){
        $zip = new ZipArchive;
        if ($zip->open($docxFilePath) === TRUE) {
            $documentXml = $zip->getFromName('word/document.xml');
            $relsXml = $zip->getFromName('word/_rels/document.xml.rels');
            $zip->close();
        } else {
            throw new Exception("Cannot open DOCX file.");
        }
    
        $rels = simplexml_load_string($relsXml);
        $emfRIds = [];
    
        foreach ($rels->Relationship as $rel) {
            $target = (string) $rel['Target'];
            $rId = (string) $rel['Id'];
    
            if (str_ends_with(strtolower($target), '.emf')) {
                $emfRIds[] = $rId;
            }
        }
    
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadXML($documentXml);
        $xpath = new DOMXPath($dom);
    
        $xpath->registerNamespace("w", "http://schemas.openxmlformats.org/wordprocessingml/2006/main");
        $xpath->registerNamespace("a", "http://schemas.openxmlformats.org/drawingml/2006/main");
        $xpath->registerNamespace("r", "http://schemas.openxmlformats.org/officeDocument/2006/relationships");
    
        $results = [];
    
        foreach ($emfRIds as $rId) {
            $blips = $xpath->query("//a:blip[@r:embed='$rId']");
        
            foreach ($blips as $blip) {
                $contextText = '';
        
                // Step 1: Go up to <w:p>
                $node = $blip;
                $paragraph = null;
                while ($node && $node->nodeName !== 'w:p') {
                    $node = $node->parentNode;
                }
        
                if ($node && $node->nodeName === 'w:p') {
                    $paragraph = $node;
                }
        
                $allTexts = [];
        
                // Step 2: Extract text from current <w:p>
                if ($paragraph) {
                    $currentTexts = $xpath->query(".//w:t", $paragraph);
                    foreach ($currentTexts as $textNode) {
                        $allTexts[] = $textNode->textContent;
                    }
        
                    // Step 3: If empty, check previous <w:p>
                    if (empty($allTexts)) {
                        $prev = $paragraph->previousSibling;
                        for ($i = 0; $i < 5 && $prev; $i++) {
                            while ($prev && $prev->nodeName !== 'w:p') {
                                $prev = $prev->previousSibling;
                            }
                    
                            if ($prev && $prev->nodeName === 'w:p') {
                                $prevTexts = $xpath->query(".//w:t", $prev);
                                foreach ($prevTexts as $textNode) {
                                    $allTexts[] = $textNode->textContent;
                                }
                                $prev = $prev->previousSibling;
                            }
                        }
                    }
        
                    // Step 4: If still empty, check next <w:p>
                    if (empty($allTexts)) {
                        $next = $paragraph->nextSibling;
                        for ($i = 0; $i < 5 && $next; $i++) {
                            while ($next && $next->nodeName !== 'w:p') {
                                $next = $next->nextSibling;
                            }
                    
                            if ($next && $next->nodeName === 'w:p') {
                                $nextTexts = $xpath->query(".//w:t", $next);
                                foreach ($nextTexts as $textNode) {
                                    $allTexts[] = $textNode->textContent;
                                }
                                $next = $next->nextSibling;
                            }
                        }
                    }
                }
        
                $results[] = [
                    'rId' => $rId,
                    'context' => trim(implode('<br>', $allTexts)) ?: 'Unknown place',
                ];
                //break;
            }
            if(count($results) > 0){
                //break;
            }
        }
        
        /*$final_result = isset($results[0]['context']) ? $results[0]['context'] : '';
        $without_br = str_replace('<br>', "", $final_result);
        if(strlen($without_br) < 30 && isset($results[1]['context'])){
            $final_result .= $results[1]['context'];
        }*/
        //file_put_contents('sss.txt', $without_br);
        return $results;
    }

    private function extractQuestionsFromWord_NEET($filePath, $qbankId){
        $emfError = $this->alertEmfFileError($filePath);
        if($emfError){
            return back()->with('custom_alert', $emfError);
        }
        //$convertedFilePath = $filePath;;
        $convertedFilePath = $this->convertMathMLToPlainText($filePath);
        
        if($convertedFilePath === -1){
            return back()->with('alert', generate_alert(__('Failed to convert MathML to plain text.'), 'danger'));
        }else if($convertedFilePath !== 0){
            $filePath = $convertedFilePath;
        }
        $phpWord = IOFactory::load($filePath);
        $questions = [];

        $text = $this->parseExamContentFromWord($phpWord, $qbankId);
        //exit;
        //file_put_contents('s.txt', $text);
        //p($text);
        $pattern = '/
            \[Q\]\s*(.*?)\n              # Capture the question
            \s*\(a\)\s*(.*?)\n?          # Capture option (a)
            \s*\(b\)\s*(.*?)\n?          # Capture option (b)
            \s*\(c\)\s*(.*?)\n?          # Capture option (c)
            \s*\(d\)\s*(.*?)\n?          # Capture option (d)
            (?:\s*\(e\)\s*(.*?)\n?)?     # Optional: Capture option (e)
            \[qtype\].*?\n               # Skip [qtype] mcq
            \[ans\]\s*([a-e])\n?         # Capture correct answer
            \[Marks\].*?\n?              # Skip [Marks]
            \[sortid\].*?\n?             # Skip [sortid]
            (?:\(?[a-e]\)?\n)?           # Skip additional correct answer (a), (b), (c), (d), or (e)
            ([^[]*)                      # Capture explanation before [soln]
            (?=\[soln\]|\Z)              # Stop at [soln] or end of text
        /sx';

        preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);
        //p($matches);

        $questions = [];

        foreach ($matches as $match) {
            
            // Trim all matches to remove extra spaces
            $match = array_map('trim', $match);
            //p($match); 

            // Build the answer options dynamically
            $answerMap = [
                "a" => $match[2] ?? '',
                "b" => $match[3] ?? '',
                "c" => $match[4] ?? '',
                "d" => $match[5] ?? '',
                "e" => $match[6] ?? '',
            ];

            $correctAnswerKey = strtolower($match[7]);
            $correctAnswerKey = trim(preg_replace('/\s+/', ' ', $correctAnswerKey));
            $correctAnswerText = $answerMap[$correctAnswerKey] ?? '';
            $explanation = trim($match[8] ?? '');
            $explanation = explode(')', $explanation, 2);
            $explanation = trim($explanation[1] ?? '');
            /*if(strtolower($explanation) == 'not available'){
                $explanation = '';
            }*/

            $questions[] = [
                'question' => trim($match[1]),
                'option_a' => $answerMap['a'],
                'option_b' => $answerMap['b'],
                'option_c' => $answerMap['c'],
                'option_d' => $answerMap['d'],
                'option_e' => $answerMap['e'],
                'correct_answer' => $correctAnswerText,
                'explanation' => $explanation,
            ];
        }
        //p($questions);

        return $questions;
    }
}