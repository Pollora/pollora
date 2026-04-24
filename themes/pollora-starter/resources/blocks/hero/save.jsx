import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Save({ attributes }) {
    const { heading, description, buttonText, buttonUrl } = attributes;
    const blockProps = useBlockProps.save();

    return (
        <div {...blockProps}>
            <div className="absolute inset-0 overflow-hidden pointer-events-none">
                <div className="absolute -top-10 -right-10 w-72 h-72 bg-white/10 rounded-full blur-3xl" />
                <div className="absolute -bottom-16 -left-16 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl" />
            </div>

            <div className="relative z-10 max-w-3xl mx-auto text-center">
                <div className="inline-flex items-center gap-2 px-4 py-1.5 mb-6 text-sm font-medium text-indigo-200 bg-white/10 rounded-full backdrop-blur-sm border border-white/20">
                    <span className="w-2 h-2 bg-emerald-400 rounded-full animate-pulse" />
                    New release available
                </div>

                <RichText.Content
                    tagName="h1"
                    className="text-5xl sm:text-6xl font-extrabold tracking-tight text-white mb-6 leading-[1.1]"
                    value={heading}
                />

                <RichText.Content
                    tagName="p"
                    className="text-xl text-indigo-100/80 mb-10 max-w-2xl mx-auto leading-relaxed"
                    value={description}
                />

                <div className="flex flex-wrap items-center justify-center gap-4">
                    <a href={buttonUrl} className="group relative inline-block">
                        <span className="absolute -inset-0.5 bg-gradient-to-r from-pink-500 to-violet-500 rounded-xl opacity-75 blur group-hover:opacity-100 transition" />
                        <RichText.Content
                            tagName="span"
                            className="relative inline-flex items-center gap-2 px-8 py-3.5 bg-white text-gray-900 font-semibold rounded-xl"
                            value={buttonText}
                        />
                    </a>
                    <a href="#learn-more" className="inline-flex items-center gap-2 px-8 py-3.5 text-white font-medium border border-white/25 rounded-xl hover:bg-white/10 transition">
                        Learn more
                    </a>
                </div>
            </div>
        </div>
    );
}
