<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Todo App</title>
  <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: "Inter", "Segoe UI", Arial, sans-serif;
        min-height: 100vh;
        background:
            radial-gradient(circle at top left, #dbeafe 0, transparent 35%),
            radial-gradient(circle at bottom right, #ede9fe 0, transparent 35%),
            #f8fafc;
        padding: 50px 20px;
        color: #1e293b;
    }

    .container {
        width: 100%;
        max-width: 700px;
        margin: 0 auto;
        background: rgba(255, 255, 255, 0.92);
        padding: 35px;
        border-radius: 24px;
        box-shadow:
            0 20px 50px rgba(15, 23, 42, 0.10),
            0 4px 12px rgba(15, 23, 42, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    /* Header */
    h1 {
        text-align: center;
        font-size: 36px;
        font-weight: 800;
        margin-bottom: 8px;
        letter-spacing: -1px;
        color: #0f172a;
    }

    .subtitle {
        text-align: center;
        color: #64748b;
        font-size: 15px;
        margin-bottom: 30px;
    }

    /* Add Todo Form */
    form {
        display: flex;
        gap: 12px;
        margin-bottom: 30px;
    }

    input[type="text"] {
        flex: 1;
        height: 52px;
        padding: 0 18px;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        outline: none;
        font-size: 16px;
        color: #1e293b;
        background: #f8fafc;
        transition: 0.25s ease;
    }

    input[type="text"]:focus {
        border-color: #6366f1;
        background: white;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
    }

    button {
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: 0.25s ease;
    }

    form button {
        height: 52px;
        padding: 0 24px;
        border-radius: 14px;
        background: #6366f1;
        color: white;
        font-size: 15px;
        box-shadow: 0 8px 18px rgba(99, 102, 241, 0.25);
    }

    form button:hover {
        background: #4f46e5;
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(99, 102, 241, 0.30);
    }

    /* Todo List */
    ul {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    li {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 17px 18px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        transition: 0.25s ease;
    }

    li:hover {
        background: white;
        transform: translateY(-2px);
        border-color: #c7d2fe;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.07);
    }

    li span {
        flex: 1;
        font-size: 16px;
        font-weight: 500;
        color: #334155;
        word-break: break-word;
    }

    /* Buttons inside todo items */
    li button {
        padding: 9px 14px;
        border-radius: 10px;
        font-size: 13px;
    }

    li button:hover {
        transform: translateY(-1px);
    }

    /* Delete button */
    li button[type="submit"] {
        background: #fee2e2;
        color: #dc2626;
    }

    li button[type="submit"]:hover {
        background: #fecaca;
    }

    /* Completed todo */
    li.completed {
        opacity: 0.65;
        background: #f1f5f9;
    }

    li.completed span {
        text-decoration: line-through;
        color: #64748b;
    }

    /* Empty state */
    .empty {
        text-align: center;
        padding: 45px 20px;
        color: #94a3b8;
    }

    .empty-icon {
        font-size: 45px;
        margin-bottom: 12px;
    }

    .empty p {
        font-size: 16px;
    }

    /* Mobile */
    @media (max-width: 600px) {
        body {
            padding: 20px 12px;
        }

        .container {
            padding: 25px 18px;
            border-radius: 20px;
        }

        h1 {
            font-size: 29px;
        }

        form {
            flex-direction: column;
        }

        form button {
            width: 100%;
        }

        li {
            padding: 14px;
        }
    }
</style>
</head>

<body>
  <h1>My Tasks ✨</h1>

   <p class="subtitle">
    Stay organized. Get things done.
   </p>

    <form id="todoForm">
        <input
            type="text"
            id="todoInput"
            placeholder="Enter your todo"
            required
        >

        <button type="submit">Add Todo</button>
    </form>

    <ul id="todoList"></ul>
    <script>
    const todoForm = document.getElementById('todoForm');
    const todoInput = document.getElementById('todoInput');
    const todoList = document.getElementById('todoList');

    // Load todos
    async function loadTodos() {
        const response = await fetch('/todos');
        const todos = await response.json();

        todoList.innerHTML = '';

        todos.forEach(todo => {
    const li = document.createElement('li');

    // Checkbox
    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.checked = todo.completed;

    checkbox.addEventListener('change', async function () {
        await fetch(`/todos/${todo.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                completed: checkbox.checked
            })
        });

        loadTodos();
    });

    // Todo text
    const text = document.createElement('span');
    text.textContent = ' ' + todo.title;

 if (todo.completed) {
    text.style.opacity = '0.5';
}
    // Edit button
const editButton = document.createElement('button');
editButton.textContent = ' Edit';

editButton.addEventListener('click', async function () {
    const newTitle = prompt('Edit your todo:', todo.title);

    if (newTitle === null || newTitle.trim() === '') {
        return;
    }

    await fetch(`/todos/${todo.id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            title: newTitle.trim()
        })
    });

    loadTodos();
});

    // Delete button
    const deleteButton = document.createElement('button');
    deleteButton.textContent = ' Delete';

    deleteButton.addEventListener('click', async function () {
        await fetch(`/todos/${todo.id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
            }
        });

        loadTodos();
    });

    li.appendChild(checkbox);
li.appendChild(text);
li.appendChild(editButton);
li.appendChild(deleteButton);
    todoList.appendChild(li);
});
    }

    // Add todo
    todoForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        const title = todoInput.value;

        const response = await fetch('/todos', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                title: title
            })
        });

        if (response.ok) {
            todoInput.value = '';
            loadTodos();
        }
    });

    // Load todos when page opens
    loadTodos();
</script>
</body>
</html>