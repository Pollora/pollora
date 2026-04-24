import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
    const { heading, description, buttonText, buttonUrl } = attributes;
    const blockProps = useBlockProps();

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Button Settings', 'pollora-starter')}>
                    <TextControl
                        label={__('Button URL', 'pollora-starter')}
                        value={buttonUrl}
                        onChange={(val) => setAttributes({ buttonUrl: val })}
                    />
                </PanelBody>
            </InspectorControls>

            <div {...blockProps}>
                {/* Decorative floating shapes */}
                <div className="absolute inset-0 overflow-hidden pointer-events-none">
                    <div className="absolute -top-10 -right-10 w-72 h-72 bg-white/10 rounded-full blur-3xl" />
                    <div className="absolute -bottom-16 -left-16 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl" />
                </div>

                <div className="relative z-10 max-w-3xl mx-auto text-center">
                    <div className="inline-flex items-center gap-2 px-4 py-1.5 mb-6 text-sm font-medium text-indigo-200 bg-white/10 rounded-full backdrop-blur-sm border border-white/20">
                        <span className="w-2 h-2 bg-emerald-400 rounded-full animate-pulse" />
                        {__('New release available', 'pollora-starter')}
                    </div>

                    <RichText
                        tagName="h1"
                        className="text-5xl sm:text-6xl font-extrabold tracking-tight text-white mb-6 leading-[1.1]"
                        value={heading}
                        onChange={(val) => setAttributes({ heading: val })}
                        placeholder={__('Build something amazing', 'pollora-starter')}
                    />

                    <RichText
                        tagName="p"
                        className="text-xl text-indigo-100/80 mb-10 max-w-2xl mx-auto leading-relaxed"
                        value={description}
                        onChange={(val) => setAttributes({ description: val })}
                        placeholder={__('A modern framework that bridges Laravel and WordPress for a delightful developer experience.', 'pollora-starter')}
                    />

                    <div className="flex flex-wrap items-center justify-center gap-4">
                        <div className="group relative">
                            <div className="absolute -inset-0.5 bg-gradient-to-r from-pink-500 to-violet-500 rounded-xl opacity-75 blur group-hover:opacity-100 transition" />
                            <RichText
                                tagName="span"
                                className="relative inline-flex items-center gap-2 px-8 py-3.5 bg-white text-gray-900 font-semibold rounded-xl cursor-pointer"
                                value={buttonText}
                                onChange={(val) => setAttributes({ buttonText: val })}
                                placeholder={__('Get Started →', 'pollora-starter')}
                            />
                        </div>
                        <span className="inline-flex items-center gap-2 px-8 py-3.5 text-white font-medium border border-white/25 rounded-xl hover:bg-white/10 transition cursor-pointer">
                            {__('Learn more', 'pollora-starter')}
                        </span>
                    </div>
                </div>
            </div>
        </>
    );
}
