<!DOCTYPE html>
<html>
<head>
    <title>Test Permissions</title>
</head>
<body>
    <h1>Test des Permissions</h1>
    
    <h2>Auth User Info:</h2>
    <p>User ID: {{ auth()->id() }}</p>
    <p>User Name: {{ auth()->user()->name }}</p>
    <p>User Email: {{ auth()->user()->email }}</p>
    
    <h2>Test @can directive:</h2>
    
    @can('view_products')
        <p style="color: green;">✓ CAN view_products</p>
    @else
        <p style="color: red;">✗ CANNOT view_products</p>
    @endcan
    
    @can('view_categories')
        <p style="color: green;">✓ CAN view_categories</p>
    @else
        <p style="color: red;">✗ CANNOT view_categories</p>
    @endcan
    
    @can('view_orders')
        <p style="color: green;">✓ CAN view_orders</p>
    @else
        <p style="color: red;">✗ CANNOT view_orders</p>
    @endcan
    
    <h2>Test @canany directive:</h2>
    
    @canany(['view_products', 'view_categories'])
        <p style="color: green;">✓ CAN ANY (view_products OR view_categories)</p>
    @else
        <p style="color: red;">✗ CANNOT ANY</p>
    @endcanany
    
    <h2>Direct check with auth()->user()->can():</h2>
    <p>view_products: {{ auth()->user()->can('view_products') ? 'YES' : 'NO' }}</p>
    <p>view_categories: {{ auth()->user()->can('view_categories') ? 'YES' : 'NO' }}</p>
    <p>view_orders: {{ auth()->user()->can('view_orders') ? 'YES' : 'NO' }}</p>
    
    <h2>All User Permissions:</h2>
    <ul>
        @foreach(auth()->user()->getAllPermissions() as $permission)
            <li>{{ $permission->name }}</li>
        @endforeach
    </ul>
</body>
</html>
