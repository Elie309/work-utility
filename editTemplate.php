<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Description Template</title>
    <link rel="stylesheet" href="output.css">


</head>

<body class="font-sans">
    <div class="container mx-auto max-w-2xl">
        <header class="p-5 bg-green-600 text-white">
            <a href="descriptions.php" class="font-bold text-white underline">Back</a>
            <h1 class="text-2xl font-bold">Edit Description Template</h1>
        </header>
        <label for="templateSelect" class="block mt-4">Select Template:</label>
        <select id="templateSelect" class="block w-full mt-2 p-2 border rounded" onchange="loadTemplate()"></select>
        <form onsubmit="event.preventDefault(); saveTemplate();" class="mt-4">
            <label for="templateName" class="block mt-4">Template Name:</label>
            <input id="templateName" type="text" class="block w-full mt-2 p-2 border rounded" required>

            <label for="fields" class="block mt-4">Fields (format: key: placeholder):</label>
            <textarea id="fields" class="block w-full mt-2 p-2 border rounded" rows="10" required>
                title: Enter the title
            </textarea>

            <div class="flex justify-around">
                <button type="button" onclick="deleteTemplate()" class="mt-4 bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700">Delete Template</button>
                <button type="submit" class="mt-4 bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">Save Template</button>
            </div>
        </form>
    </div>

    <script defer>
        async function loadTemplate() {
            const templateName = document.getElementById('templateSelect').value;
            const response = await fetch(`templates/${templateName}.json`);
            const fields = await response.json();
            document.getElementById('templateName').value = templateName;
            document.getElementById('fields').value = Object.entries(fields).map(([key, value]) => `${key}: ${value}`).join('\n');
        }

        async function saveTemplate() {
            const templateName = document.getElementById('templateName').value;
            const fields = document.getElementById('fields').value.split('\n').reduce((acc, field) => {
                const [key, value] = field.split(':');
                if (key && value) {
                    acc[key.trim()] = value.trim();
                }
                return acc;
            }, {});

            const response = await fetch('handlers/saveTemplate.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    templateName,
                    fields
                })
            });

            if (response.ok) {
                alert('Template saved!');
                loadAllTemplates();
            } else {
                alert('Failed to save template.');
            }
        }

        async function deleteTemplate() {
            const templateName = document.getElementById('templateName').value;
            const response = await fetch('handlers/deleteTemplate.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    templateName
                })
            });

            if (response.ok) {
                alert('Template deleted!');
                loadAllTemplates();
            } else {
                alert('Failed to delete template.');
            }
        }

        async function loadAllTemplates() {
            fetch('handlers/getTemplates.php')
                .then(response => response.json())
                .then(templates => {
                    const templateSelect = document.getElementById('templateSelect');
                    templateSelect.innerHTML = '';
                    for (const key in templates) {
                        const option = document.createElement('option');
                        option.value = templates[key];
                        option.textContent = templates[key].charAt(0).toUpperCase() + templates[key].slice(1);
                        templateSelect.appendChild(option);
                    }

                    templateSelect.dispatchEvent(new Event('change'));
                });
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadAllTemplates();
        });
    </script>
</body>

</html>