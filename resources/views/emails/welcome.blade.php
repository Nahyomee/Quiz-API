<x-mail::message>
Dear {{$user->name}},

<p>Thanks for trying out my Quiz App, it really means a lot to me.</p>
<p>These are some of the features of the quiz app:</p>
<ol>
    <li> Create Quizzes</li>
    <li> Take Quizzes</li>
    <li> Share your results</li>
</ol>
<p>This is just the beginning and I hope i can keep adding more features.</p>
<p>If you enjoy it, you can star my github repository using the link below.</p>

<x-mail::button :url="$url">
Click me
</x-mail::button>

Thanks,<br>
Nahyomee.
</x-mail::message>
