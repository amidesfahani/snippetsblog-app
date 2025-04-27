<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snippets</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- CodeMirror -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
    <!-- CodeMirror Modes -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/matchbrackets.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/php/php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/python/python.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/clike/clike.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/ruby/ruby.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/go/go.min.js"></script>
    <style>
        .CodeMirror {
            height: auto;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            font-family: 'Fira Code', monospace;
        }

        .cm-s-default .cm-variable {
            color: #3182ce;
        }

        .cm-s-default .cm-keyword {
            color: #805ad5;
        }

        .cm-s-default .cm-string {
            color: #dd6b20;
        }
    </style>
</head>

<body class="bg-gray-50">
    <nav class="bg-white shadow-sm">
        <div class="container mx-auto flex items-center justify-between px-4 py-3">
            <a href="/snippets" class="text-xl font-bold text-blue-600">Snippets</a>
            <div class="flex items-center space-x-4">
                <button id="addSnippetBtn"
                    class="rounded-md bg-blue-600 px-4 py-2 text-white transition duration-200 hover:bg-blue-700">
                    Add Snippet
                </button>
                <button id="logoutBtn" class="text-gray-600 hover:text-gray-800">Logout</button>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <h1 class="mb-4 text-2xl font-bold text-gray-800 md:mb-0">All Snippets</h1>
            <div class="flex items-center space-x-2">
                <label for="languageFilter" class="text-gray-700">Filter by Language:</label>
                <select id="languageFilter"
                    class="rounded-md border border-gray-300 px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All</option>
                    @foreach (App\Enums\SnippetLanguage::labels() as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="addSnippetModal"
            class="fixed inset-0 z-10 flex hidden items-center justify-center bg-black bg-opacity-50 max-h-screen overflow-y-auto">
            <div class="w-full max-w-2xl rounded-lg bg-white p-6">
                <h2 class="mb-4 text-xl font-bold">Add New Snippet</h2>
                <form id="addSnippetForm">
                    <div class="mb-4">
                        <label for="snippetTitle" class="mb-2 block text-gray-700">Title</label>
                        <input type="text" id="snippetTitle" name="title"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="snippetLanguage" class="mb-2 block text-gray-700">Language</label>
                        <select id="snippetLanguage" name="language"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach (App\Enums\SnippetLanguage::labels() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="snippetCode" class="mb-2 block text-gray-700">Code</label>
                        <textarea id="snippetCode" name="code" rows="10"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 font-mono focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelAddSnippet"
                            class="rounded-md border border-gray-300 px-4 py-2 transition duration-200 hover:bg-gray-100">
                            Cancel
                        </button>
                        <button type="submit"
                            class="rounded-md bg-blue-600 px-4 py-2 text-white transition duration-200 hover:bg-blue-700">
                            Save Snippet
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="snippetsContainer" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"></div>

        <div id="pagination" class="mt-8 flex justify-center"></div>
    </div>

    <script>
        $(document).ready(function() {
            const token = localStorage.getItem('jwt_token');
            if (!token) {
                window.location.href = '/login';
                return;
            }

            $.ajaxSetup({
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            });

            function loadSnippets(page = 1, language = '') {
                $.ajax({
                    url: '/api/snippets?page=' + page + (language ? '&language=' + language : ''),
                    method: 'GET',
                    success: function(response) {
                        renderSnippets(response.data);
                        renderPagination(response.links, response.meta);
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            localStorage.removeItem('jwt_token');
                            window.location.href = '/login';
                        } else {
                            alert('Failed to load snippets');
                        }
                    }
                });
            }

            function renderSnippets(snippets) {
                const container = $('#snippetsContainer');
                container.empty();

                if (snippets.length === 0) {
                    container.html('<p class="text-gray-500">No snippets found.</p>');
                    return;
                }

                snippets.forEach(snippet => {
                    const languageLabel = snippet.language.charAt(0).toUpperCase() + snippet.language.slice(
                        1);
                    const snippetHtml = `
                        <div class="overflow-hidden rounded-lg bg-white shadow-md">
                            <div class="bg-blue-600 p-4 text-white">
                                <h2 class="text-xl font-semibold">${snippet.user.username}</h2>
                            </div>
                            <div class="divide-y divide-gray-200">
                                <div class="p-4">
                                    <div class="mb-2 flex items-start justify-between">
                                        <h3 class="text-lg font-medium text-gray-800">${snippet.title}</h3>
                                        <span class="bg-${getLanguageColor(snippet.language)}-100 text-${getLanguageColor(snippet.language)}-800 rounded-full px-2 py-1 text-xs">
                                            ${languageLabel}
                                        </span>
                                    </div>
                                    <div class="mb-3">
                                        <textarea class="hidden snippet-code">${snippet.code}</textarea>
                                    </div>
                                    <div class="flex items-center justify-between text-sm text-gray-500">
                                        <div>
                                            <span class="flex items-center">
                                                <svg class="mx-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                                </svg>
                                                ${snippet.likes_count}
                                                <button class="ml-2 text-blue-600 hover:text-blue-800 like-btn" data-snippet-id="${snippet.id}">
                                                    Like
                                                </button>
                                            </span>
                                        </div>
                                        <div>
                                            <span>${new Date(snippet.created_at).toLocaleDateString()}</span>
                                        </div>
                                    </div>
                                    <div class="mt-3 border-t border-gray-200 pt-3">
                                        <div class="mb-2 flex items-center justify-between">
                                            <h4 class="text-sm font-medium text-gray-700">Comments (${snippet.comments_count})</h4>
                                        </div>
                                        <div id="comments-${snippet.id}" class="space-y-2">
                                            ${renderComments(snippet.comments)}
                                        </div>
                                        <form class="mt-3 add-comment-form" data-snippet-id="${snippet.id}">
                                            <textarea name="comment" rows="2" class="w-full px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Add a comment..."></textarea>
                                            <button type="submit" class="mt-1 bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 transition duration-200 text-sm">
                                                Post Comment
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    container.append(snippetHtml);

                    const textarea = $(`#snippetsContainer .snippet-code`).last()[0];
                    CodeMirror.fromTextArea(textarea, {
                        lineNumbers: true,
                        readOnly: true,
                        mode: getCodeMirrorMode(snippet.language),
                        theme: 'default',
                        lineWrapping: true,
                        matchBrackets: true,
                        indentUnit: 4
                    });
                });
            }

            function renderComments(comments) {
                if (!comments || comments.length === 0) {
                    return '<p class="text-sm text-gray-500">No comments yet</p>';
                }

                console.log(comments);

                return comments.map(comment => `
                    <div class="text-sm text-gray-600">
                        <p class="font-bold">${comment.user.username}</p>
                        <p>${comment.comment}</p>
                        <p class="text-xs text-gray-400">${new Date(comment.created_at).toLocaleString()}</p>
                    </div>
                `).join('');
            }

            function renderPagination(links, meta) {
                const pagination = $('#pagination');
                pagination.empty();

                if (!links || !meta) return;

                let html = '<div class="flex items-center justify-between w-full">';

                html += `<div class="text-sm text-gray-600">Page ${meta.current_page} of ${meta.last_page}</div>`;

                html += '<div class="flex space-x-2">';

                if (meta.current_page > 1) {
                    html += `<button class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-100 transition duration-200 pagination-btn" data-page="1">First</button>`;
                }

                if (links.prev) {
                    html += `<button class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-100 transition duration-200 pagination-btn" data-page="${meta.current_page - 1}">Previous</button>`;
                }

                html += `<button class="px-3 py-1 border bg-blue-600 text-white rounded-md cursor-default" disabled>${meta.current_page}</button>`;

                if (links.next) {
                    html += `<button class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-100 transition duration-200 pagination-btn" data-page="${meta.current_page + 1}">Next</button>`;
                }

                if (meta.current_page < meta.last_page) {
                    html += `<button class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-100 transition duration-200 pagination-btn" data-page="${meta.last_page}">Last</button>`;
                }

                html += '</div>';
                html += '</div>';

                pagination.html(html);
            }

            const SnippetLanguageColors = @json(\App\Enums\SnippetLanguage::colors());
            const SnippetLanguageModes = @json(\App\Enums\SnippetLanguage::codeMirrorModes());

            function getLanguageColor(language) {
                return SnippetLanguageColors[language.toLowerCase()] || 'gray';
            }

            function getCodeMirrorMode(language) {
                return SnippetLanguageModes[language.toLowerCase()] || 'text/plain';
            }

            $('#languageFilter').change(function() {
                const language = $(this).val();
                loadSnippets(1, language);
            });

            $(document).on('click', '.pagination-btn', function() {
                const page = $(this).data('page');
                const language = $('#languageFilter').val();
                loadSnippets(page, language);
            });

            $('#addSnippetBtn').click(function() {
                $('#addSnippetModal').removeClass('hidden');
            });

            $('#cancelAddSnippet').click(function() {
                $('#addSnippetModal').addClass('hidden');
                $('#addSnippetForm')[0].reset();
            });

            $('#addSnippetForm').submit(function(e) {
                e.preventDefault();
                const formData = {
                    title: $('#snippetTitle').val(),
                    language: $('#snippetLanguage').val(),
                    code: $('#snippetCode').val()
                };

                $.ajax({
                    url: '/api/snippets',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#addSnippetModal').addClass('hidden');
                        $('#addSnippetForm')[0].reset();
						loadSnippets();
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            localStorage.removeItem('jwt_token');
                            window.location.href = '/login';
                        } else {
                            alert('Failed to add snippet');
                        }
                    }
                });
            });

            $(document).on('submit', '.add-comment-form', function(e) {
                e.preventDefault();
                const snippetId = $(this).data('snippet-id');
                const comment = $(this).find('textarea').val();

                $.ajax({
                    url: `/api/snippets/${snippetId}/comments`,
                    method: 'POST',
                    data: {
                        comment
                    },
                    success: function(response) {
						const page = $(this).data('page');
                		const language = $('#languageFilter').val();
                		loadSnippets(page, language);
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            localStorage.removeItem('jwt_token');
                            window.location.href = '/login';
                        } else {
                            alert('Failed to add comment');
                        }
                    }
                });
            });

            $(document).on('click', '.like-btn', function() {
                const snippetId = $(this).data('snippet-id');

                $.ajax({
                    url: `/api/snippets/${snippetId}/like`,
                    method: 'POST',
                    success: function(response) {
                        const page = $(this).data('page');
                		const language = $('#languageFilter').val();
                		loadSnippets(page, language);
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            localStorage.removeItem('jwt_token');
                            window.location.href = '/login';
                        } else {
                            alert(xhr.responseJSON.message || 'Failed to like snippet');
                        }
                    }
                });
            });

            $('#logoutBtn').click(function() {
                $.ajax({
                    url: '/api/auth/logout',
                    method: 'POST',
                    success: function() {
                        localStorage.removeItem('jwt_token');
                        window.location.href = '/login';
                    },
                    error: function() {
                        localStorage.removeItem('jwt_token');
                        window.location.href = '/login';
                    }
                });
            });

            loadSnippets();
        });
    </script>
</body>

</html>
