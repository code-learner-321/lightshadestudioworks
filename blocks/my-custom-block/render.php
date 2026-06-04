<?php
// Access block attributes if needed
$wrapper_attributes = get_block_wrapper_attributes(
    array(
        'class' => 'p-6 bg-gray-100 rounded-lg shadow-md',
    )
);
?>
<div <?php echo $wrapper_attributes; ?> >
    <h1 class="text-4xl font-bold text-blue-600">Hello from my custom block!</h1>
    <p class="mt-2 text-red-600">This block is powered by your hybrid theme structure and Tailwind CSS.</p>
</div>