<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Description Generator</title>
    <link rel="stylesheet" href="output.css">

    <style>
        #formLayout input,
        textarea {

            outline: none;
            border: 1px solid #d1d1d1;
            padding: 10px;
        }
    </style>
    <script defer>
        document.addEventListener('DOMContentLoaded', (event) => {
            const templateSelect = document.getElementById('templateSelect');
            templateSelect.addEventListener('change', loadTemplate);

            function handleSelectTemplate() {
                fetch('handlers/getTemplates.php')
                    .then(response => response.json())
                    .then(data => {
                        const templateSelect = document.getElementById('templateSelect');
                        templateSelect.innerHTML = '';
                        for (const key in data) {
                            const option = document.createElement('option');
                            option.value = data[key];
                            option.textContent = data[key].charAt(0).toUpperCase() + data[key].slice(1);
                            templateSelect.appendChild(option);
                        }

                        templateSelect.dispatchEvent(new Event('change'));

                    });
            }

            function loadTemplate() {
                const template = templateSelect.value;
                fetch(`templates/${template}.json`)
                    .then(response => response.json())
                    .then(data => {
                        const formContainer = document.getElementById('formContainer');
                        formContainer.innerHTML = ''; // Clear existing form fields
                        for (const key in data) {
                            const label = document.createElement('label');
                            label.setAttribute('for', key);
                            label.classList.add('col-span-2');
                            label.textContent = `${key.charAt(0).toUpperCase() + key.slice(1)}:`;
                            const input = document.createElement('input');
                            input.classList.add('col-span-7');
                            input.setAttribute('type', 'text');
                            input.setAttribute('id', key);
                            input.setAttribute('name', key);
                            input.setAttribute('placeholder', data[key]);
                            input.required = true;
                            formContainer.appendChild(label);
                            formContainer.appendChild(input);
                        }
                    });
            }

            // Load the default template
            handleSelectTemplate();
            loadTemplate();
        });




        function generateDescription() {
            const form = document.forms['itemForm'];
            let description = `${form['name'].value}\n`;
            for (const element of form.elements) {
                if (element.name && element.name !== 'name') {
                    description += `- ${element.name.charAt(0).toUpperCase() + element.name.slice(1)}: ${element.value}\n`;
                }
            }
            document.getElementById('description').value = description.trim();
        }

        function copyToClipboard() {
            const description = document.getElementById('description');
            const copyButton = document.getElementById('copyButton');
            description.select();
            document.execCommand('copy');

            copyButton.textContent = 'Copied!';
            setTimeout(() => {
                copyButton.textContent = 'Copy to Clipboard';
            }, 2000);
        }
    </script>
</head>

<body class="w-full mx-auto font-sans flex flex-col max-w-2xl">
    <header class="p-5 bg-green-600 text-white">
        <a href="index.php" class="font-bold text-white underline">Back</a>
        <h1 class="text-2xl font-bold">Item Description Generator</h1>
    </header>
    <div class="block py-5 my-5">

        <div class="flex justify-end">
            <a href="editTemplate.php" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">Edit template</a>

        </div>

        <label for="templateSelect" class="block mt-4">Select Template:</label>
        <select id="templateSelect" class="block w-full mt-2 p-2 border rounded">
        </select>

        <form id="formLayout" name="itemForm" onsubmit="event.preventDefault(); generateDescription();" class="mt-4">
            <div id="formContainer" class="grid grid-cols-9 gap-4"></div>
            <div class="flex justify-end">
                <button type="submit" class="mt-4 bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">Generate Description</button>
            </div>
        </form>

        <textarea id="description" readonly class="w-full min-h-64 mt-4 border rounded"></textarea>

        <div class="flex justify-end">
            <button onclick="copyToClipboard()" id="copyButton" class="mt-4 bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">Copy to Clipboard</button>

        </div>
    </div>
</body>

</html>